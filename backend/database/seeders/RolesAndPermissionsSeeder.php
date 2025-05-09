<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::factory()->create(['id' => 1, 'name' => 'admin']);
        $staff = Role::factory()->create(['id' => 2, 'name' => 'staff']);
        $manager = Role::factory()->create(['id' => 3, 'name' => 'manager']);

        $canCall = Permission::factory()->create(['id' => 1, 'name' => 'can_call_queue']);
        $canSkip = Permission::factory()->create(['id' => 2, 'name' => 'can_skip_queue']);
        $canManageServices = Permission::factory()->create(['id' => 3, 'name' => 'can_manage_services']);

        // Attach permissions to roles
        $admin->permissions()->attach([$canCall->id, $canSkip->id]);
        $staff->permissions()->attach([$canCall->id]);
        $manager->permissions()->attach([$canManageServices->id]);
    }
}
