<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class RedisSearchService
{
    public $keyPattern;
    private $matchedRecords = [];

    /**
     * Create a new class instance.
     */
    public function __construct(
        public Model $model,
        public string $key,
        public string $query = '',
        public string|array $searchableFields = 'name,email',
    )
    {
        $this->keyPattern = $key . ':*';
        $this->searchableFields = is_array($searchableFields)
            ? $searchableFields
            : array_map('trim', explode(',', $searchableFields));
    }

    public function checkItemsInRedisStore(): array
    {
        // If no keys exist, cache all records from DB
        if (empty($this->getRedisKeys())) {
            Log::info(
                "[RedisSearchService] Redis Cache Miss. {$this->keyPattern} keys not found in Redis.",
                [
                    'message' => 'No keys found in Redis. Fetching from DB and caching.',
                    'timestamp' => now(),
                    'query' => $this->query,
                    'keyPattern' => $this->keyPattern,
                ]
            );

            $this->storeItems();
        }

        $this->getItems();

        return $this->matchedRecords;
    }

    public function storeItemInRedis(Model $item): void
    {
        $key = $this->key . ':' . $item->getKey();

        // Store full record as JSON
        Redis::hset($key, $item->toJson());

        // Build a searchable display value from configured fields
        $sortValue = Str::lower(
            collect($this->searchableFields)
                ->map(fn($field) => $item->{$field})
                ->implode(' ')
        ); // or any other sorting logic

        // Add to sorted set
        Redis::zadd($this->key . ':sorted', 0, json_encode([
            'id' => $item->getKey(),
            'text' => $sortValue,
        ]));
    }

    public function getRedisKeys(): array
    {
        return Redis::keys($this->keyPattern);
    }

    public function storeItems(): void
    {
        // Fetch all records from the DB
        // and cache them in Redis
        $allRecords = ($this->model)::orderBy('id')->get();

        foreach ($allRecords as $record) {
            $data = ['id' => $record->id];

            foreach ($this->searchableFields as $field) {
                $data[$field] = $record->{$field};
            }

            $key = "{$this->key}:{$record->id}";

            // Store hash
            Redis::hmset($key, $data);

            // Add to sorted set for ordering
            Redis::zadd("{$this->key}:sorted", $record->id, $key);
        }
    }

    public function getItems()
    {
        $sortedKeys = Redis::zrange("{$this->key}:sorted", 0, -1);

        foreach ($sortedKeys as $key) {
            $record = Redis::hgetall($key);

            if (empty($this->query)) {
                $this->matchedRecords[] = $record;
            } else {
                foreach ($this->searchableFields as $field) {
                    if (
                        isset($record[$field]) &&
                        str_contains(strtolower($record[$field]), strtolower($this->query))
                    ) {
                        $this->matchedRecords[] = $record;
                        break;
                    }
                }
            }
        }
    }

    public function updateItem(Model $record): void
    {
        $key = "{$this->key}:{$record->id}";

        $data = ['id' => $record->id];
        foreach ($this->searchableFields as $field) {
            $data[$field] = $record->{$field};
        }

        Log::info('[RedisSearchService] Updating Redis Cache', [
            'key' => $key,
            'data' => $data,
        ]);

        // Store hash
        Redis::hmset($key, $data);

        // Add to sorted set for ordering
        Redis::zadd("{$this->key}:sorted", $record->id, $key);
    }

    public function deleteItem(Model $record): void
    {
        $key = "{$this->key}:{$record->id}";

        Log::info('[RedisSearchService] Deleting Redis Cache', [
            'key' => $key,
            'id' => $record->id,
        ]);

        Redis::del($key);
        Redis::zrem("{$this->key}:sorted", $key);
    }

    public function clearCache()
    {
        Log::info("[RedisSearchService]: Clearing {$this->key} Cache");

        $keys = $this->getRedisKeys();

        if (!empty($keys)) {
            Redis::del(...$keys); // Delete all matching keys
        }
    }
}
