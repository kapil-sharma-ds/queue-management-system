<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RedisSearchService
{
    public $key;
    private $matchedRecords = [];

    /**
     * Create a new class instance.
     */
    public function __construct(
        public Model $model,
        public string $keyPattern,
        public string $query,
        public string|array $searchableFields = 'name,email',
    )
    {
        $this->key = $this->keyPattern;
        $this->keyPattern = $this->keyPattern . ':*';
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
}
