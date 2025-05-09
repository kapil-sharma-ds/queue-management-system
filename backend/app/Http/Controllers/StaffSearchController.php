<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Staff;
use Illuminate\Support\Facades\Log;

class StaffSearchController extends Controller
{
    public function showSearchForm(Request $request)
    {
        return view('staff.search');
    }

    public function search(Request $request)
    {
        $query = strtolower($request->input('query'));
        $matchedStaff = [];

        // Key Pattern to search in Redis
        // This pattern assumes that all staff records are stored with a prefix 'staff:*'
        $keyPattern = 'staff:*';

        // Fetch all staff keys
        $redisCachedKeys = Redis::keys($keyPattern);

        // If no keys exist, cache all staff from DB
        if (empty($redisCachedKeys)) {
            Log::info(
                "[StaffSearchController] Redis Cache Miss. {$keyPattern} keys not found in Redis.",
                [
                    'message' => 'No keys found in Redis. Fetching from DB and caching.',
                    'timestamp' => now(),
                    'query' => $query,
                    'keyPattern' => $keyPattern,
                ]
            );

            // Fetch all staff from the database
            // and cache them in Redis
            $staffList = Staff::all();

            foreach ($staffList as $staff) {
                Redis::hmset("staff:{$staff->id}", [
                    'id' => $staff->id,
                    'name' => $staff->name,
                    'email' => $staff->email,
                ]);
            }

            // Re-fetch keys after caching
            $redisCachedKeys = Redis::keys($keyPattern);
        }

        if (empty($query)) {
            foreach ($redisCachedKeys as $key) {
                $matchedStaff[] = Redis::hgetall($key);
            }
        } else {
            // Search in cached Redis records
            foreach ($redisCachedKeys as $key) {
                $staff = Redis::hgetall($key);

                if (
                    isset($staff['name']) && str_contains(strtolower($staff['name']), $query) ||
                    isset($staff['email']) && str_contains(strtolower($staff['email']), $query)
                ) {
                    $matchedStaff[] = $staff;
                }
            }
        }

        return view('staff.search', ['results' => $matchedStaff, 'query' => $query]);
    }
}
