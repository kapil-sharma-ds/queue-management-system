<?php

namespace Database\Factories;

use App\Models\Counter;
use App\Models\Role;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'bio' => $this->faker->paragraph(),
            'password' => Hash::make('password'), // or bcrypt()
            'role_id' => $this->faker
                ->randomElement(
                    Role::pluck('id')->toArray()
                ),
            'service_id' => $this->faker
                ->randomElement(
                    Service::pluck('id')->toArray()
                ),
            'counter_id' => $this->faker
                ->randomElement(
                    Counter::pluck('id')->toArray()
                ),
        ];
    }
}
