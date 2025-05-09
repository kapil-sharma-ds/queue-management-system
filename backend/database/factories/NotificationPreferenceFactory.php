<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationPreference>
 */
class NotificationPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'staff_id' => $this->faker
                ->randomElement(
                    Staff::pluck('id')->toArray()
                ),
            'notify_on_new' => $this->faker->boolean(),
            'notify_on_upcoming' => $this->faker->boolean(),
            'notify_on_skip' => $this->faker->boolean(),
            'notify_via_sms' => $this->faker->boolean(),
            'notify_via_email' => $this->faker->boolean(),
        ];
    }
}
