<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OperatingHour>
 */
class OperatingHourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => $this->faker
                ->randomElement(
                    Service::pluck('id')->toArray()
                ),
            'day_of_week' => $this->faker->numberBetween(0, 6), // Sunday to Saturday
            'opens_at' => $this->faker->time('H:i:s', '08:00:00'),
            'closes_at' => $this->faker->time('H:i:s', '17:00:00'),
        ];
    }
}
