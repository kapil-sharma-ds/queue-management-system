<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Role;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = app(Faker::class);

        $staffMembers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'bio' => $faker->paragraph(),
                'password' => Hash::make('password'),
                'role_id' => 1,
                'service_id' => 1,
                'counter_id' => 1,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'bio' => $faker->paragraph(),
                'password' => Hash::make('password'),
                'role_id' => 2,
                'service_id' => 2,
                'counter_id' => 2,
            ],
        ];

        foreach ($staffMembers as $staff) {
            Staff::factory()->create($staff);
        }

        Staff::factory()->count(10)->create([
            'role_id' => $faker->randomElement(
                    Role::pluck('id')->toArray()
                ),
            'service_id' => $faker
                ->randomElement(
                    Service::pluck('id')->toArray()
                ),
            'counter_id' => $faker
                ->randomElement(
                    Counter::pluck('id')->toArray()
                ),
        ]);
    }
}
