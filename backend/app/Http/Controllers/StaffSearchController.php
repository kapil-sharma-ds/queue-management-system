<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Staff;
use App\Services\RedisSearchService;
use Illuminate\Support\Facades\Log;

class StaffSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = strtolower($request->input('query'));

        $matchedStaff = [];

        // Key Pattern to search in Redis
        // This pattern assumes that all staff
        // records are stored with a prefix 'staff:*'
        $keyPattern = 'staff';

        $redisSearchService = app(RedisSearchService::class, [
            'model' => new Staff,
            'keyPattern' => $keyPattern,
            'query' => $query,
            'searchableFields' => ['name', 'email'],
        ]);

        // Fetch all records from Redis
        $matchedStaff = $redisSearchService->checkItemsInRedisStore();

        return inertia('Staff/Index', [
            'staff' => $matchedStaff,
        ]);
    }

    public function showSearchForm(Request $request)
    {
        return view('staff.search');
    }

    public function search(Request $request)
    {
        $query = strtolower($request->input('query'));
        $matchedStaff = [];

        // Key Pattern to search in Redis
        // This pattern assumes that all staff
        // records are stored with a prefix 'staff:*'
        $keyPattern = 'staff';

        $redisSearchService = app(RedisSearchService::class, [
            'model' => new Staff,
            'keyPattern' => $keyPattern,
            'query' => $query,
            'searchableFields' => ['name', 'email'],
        ]);

        // Fetch all records from Redis
        $matchedStaff = $redisSearchService->checkItemsInRedisStore();

        return view('staff.search', ['results' => $matchedStaff, 'query' => $query]);
    }
}
