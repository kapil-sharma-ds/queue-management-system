<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Role;
use App\Models\Service;
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
        $key = 'staff';

        $staffModel = (new Staff);

        $redisSearchService = app(RedisSearchService::class, [
            'model' => $staffModel,
            'key' => $key,
            'query' => $query,
            'searchableFields' => $staffModel->searchableFields(),
        ]);

        // Fetch all records from Redis
        $matchedStaff = $redisSearchService->checkItemsInRedisStore();

        return inertia('Staff/Index', [
            'staff' => $matchedStaff,
        ]);
    }

    public function create()
    {
        $services = Service::all();
        $counters = Counter::all();
        $roles = Role::all();

        return inertia('Staff/Create', [
            'services' => $services,
            'counters' => $counters,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff,email',
            'bio' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
            'service_id' => 'required|exists:services,id',
            'counter_id' => 'required|exists:counters,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'password' => bcrypt($request->password),
            'service_id' => $request->service_id,
            'counter_id' => $request->counter_id,
            'role_id' => $request->role_id,
        ]);

        Log::info('[StaffSearchController]: Created Staff', [
            'staff' => $staff,
        ]);

        $redisSearchService = new RedisSearchService(
            model: new Staff(),
            key: 'staff',
            searchableFields: ['name', 'email']
        );

        $redisSearchService->storeItemInRedis($staff);

        return redirect()->route('staff.index')->with('success', 'Staff created successfully.');
    }

    public function show(Request $request)
    {
        $staff = Staff::with([
            'role',
            'service',
            'counter'
        ])->findOrFail($request->id);

        return inertia('Staff/Show', [
            'staff' => $staff,
        ]);
    }

    public function edit(Request $request)
    {
        $staff = Staff::with([
            'service',
            'counter',
            'role',
        ])->findOrFail($request->id);

        $services = Service::all();
        $counters = Counter::all();
        $roles = Role::all();

        return inertia('Staff/Edit', [
            'staff' => $staff,
            'services' => $services,
            'counters' => $counters,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request)
    {
        $staff = Staff::findOrFail($request->id);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'service_id' => $request->service_id,
            'counter_id' => $request->counter_id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(Request $request)
    {
        $staff = Staff::findOrFail($request->id);
        Log::info('Deleted Staff Id: ', [$staff->id]);
        $staff->delete();

        return response()->json(['success' => true, 'message' => 'Staff deleted successfully.']);
    }

    public function clearStaffCache()
    {
        $key = 'staff';

        $staffModel = (new Staff);

        Log::info('[StaffSearchController]: Clearing Staff Cache');

        $redisSearchService = app(RedisSearchService::class, [
            'model' => $staffModel,
            'key' => $key,
        ]);

        $redisSearchService->clearCache();

        return response()->json(['message' => 'Staff cache cleared successfully.']);
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
