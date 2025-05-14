# Redis-Based Search Implementation in Laravel

## Overview

This project provides a reusable Redis-based search service, `RedisSearchService.php`, which can be integrated into any Laravel project. It uses the `Illuminate\Support\Facades\Redis` facade (not the Cache facade) for direct and efficient communication with Redis.

---

## RedisSearchService

### Location:
`app/Services/RedisSearchService.php`

### Purpose:
Reusable Redis-based search logic, leveraging Redis hashes and sorted sets to efficiently store and retrieve searchable data.

### Constructor Signature:

```php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RedisSearchService
{
    public $keyPattern;
    private $matchedRecords = [];

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
```

### Constructor Parameters:
- model: Eloquent model instance to search.

- key: Redis key prefix.

- query: Search query string (default: empty).

- searchableFields: Fields to search on. String (comma-separated) or array. Defaults to 'name,email'.

### Class Properties:
- $keyPattern: Redis key pattern for the index (e.g., staff:*).

- $matchedRecords: Stores matched records from search.

### Methods:
- checkItemsInRedisStore(): Checks Redis, caches records if absent, returns matched records.

- getRedisKeys(): Returns Redis keys matching the pattern.

- storeItemInRedis(Model $item): Caches a single record in Redis.

- storeItems(): Caches all records from the database.

- getItems(): Retrieves matched records from Redis.

- updateItem(Model $record): Updates an item in Redis.

- deleteItem(Model $record): Deletes an item from Redis.

- clearCache(): Clears all Redis records for the given key prefix.

### Example Usage
```php
$redisSearchService = new RedisSearchService(
    model: new Staff(),
    key: 'staff',
    query: 'search_query',
    searchableFields: ['name', 'email']
);

$matchedStaff = $redisSearchService->checkItemsInRedisStore();
```

#### Notes:

> 1. Interacts directly with Redis via Redis facade.

> 2. Caches using storeItems() if Redis is initially empty.

> 3. Uses hset, zadd, del, zrem Redis commands internally.

### StaffSearchController Integration
`StaffSearchController.php` handles staff-related operations using `RedisSearchService`.

#### Controller Methods:
1. index(): GET /staff — Uses checkItemsInRedisStore() to list Redis-matched results.

2. create(): GET /staff/create — Renders form to create a new record.

3. store(): POST /staff — Creates staff record & stores it using storeItemInRedis().

4. show(): GET /staff/{id} — Retrieves a specific staff record.

5. edit(): GET /staff/{id}/edit — Displays edit form.

6. update(): PUT /staff/{id} — Updates record using model observers (triggers updateItem()).

7. destroy(): DELETE /staff/{id} — Deletes record using model observers (triggers deleteItem()).

8. clearStaffCache(): POST /staff/clear-cache — Clears all Redis keys for staff.

### Model Observers
`App\Models\Staff` implements observers inside the `booted()` method:
```php
protected static function booted()
{
    static::updated(function ($staff) {
        (new RedisSearchService(
            model: $staff,
            key: 'staff',
            query: '',
            searchableFields: ['name', 'email']
        ))->updateItem($staff);
    });

    static::deleted(function ($staff) {
        (new RedisSearchService(
            model: $staff,
            key: 'staff',
            query: '',
            searchableFields: ['name', 'email']
        ))->deleteItem($staff);
    });
}
```

## Deployment Instructions
### Environment
- Laravel 10+

- PHP 8.3.19

- WSL Ubuntu

- Docker Desktop

- Docker Compose

### Docker Services
Defined in `docker-compose.yml`:
- backend: Laravel application

- webserver: Nginx

- db: MySQL

- npm: Node.js + hot reload

- phpmyadmin: MySQL web UI

- redis: Redis container

- queue: Laravel queue worker

- Volumes: db_data

- Network: appnetwork

## Setup Steps
1. Build Docker Containers:
```sh
docker compose build
```
2. Start Containers:
```sh
docker compose up -d
```
3. Run Migrations
```sh
# Option 1: Go inside the container
docker compose exec backend bash
php artisan migrate

# Option 2: Run directly
docker compose exec backend php artisan migrate
```
4. Seed Database:
```sh
# Inside backend container
php artisan db:seed

# Or migrate with seeding directly
docker compose exec backend php artisan migrate --seed
```

## Accessing the App
Visit: http://localhost:8000
Login at: http://localhost:8000/login
Credentials:
- Email: admin@example.com
- Password: 123456

#### Note
Sometimes, when you start the docker services, the applicatio shows a blank page at `http://localhost:8000`. If a blank page is appearing, restart backend continer:
```sh
docker compose restart backend
```

## Redis Usage
Access Redis shell:
```sh
docker compose exec redis sh
```

### Useful Redis commands:
```sh
# List all Redis keys
redis-cli --scan --pattern 'staff:*'

# Delete all Redis keys
redis-cli --scan --pattern 'staff:*' | xargs redis-cli del

# View a specific Redis key
redis-cli HGETALL staff:1

# View sorted list
redis-cli ZRANGE staff:sorted 0 -1
```

## Frontend Features
- Browse all Staff records at /staff.

- Add a new records with "Create Staff" button on the listing page.

- Edit/delete records using the buttons given along each Staff record. This also updates Redis cache automatically via model observers.

- "Clear Redis Cache" button clears all Redis keys.

## Summary
This implementation efficiently integrates Redis with Laravel using a service-based approach and Redis commands under the hood. Ideal for performance-critical applications with frequent read operations.