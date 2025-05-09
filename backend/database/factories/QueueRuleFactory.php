<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Counter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QueueRule>
 */
class QueueRuleFactory extends Factory
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
            'counter_id' => $this->faker
                ->randomElement(
                    Counter::pluck('id')->toArray()
                ),
            'max_wait_time' => $this->faker->numberBetween(10, 60), // in minutes
            'auto_skip_time' => $this->faker->numberBetween(5, 30), // in minutes
            'notify_before' => $this->faker->numberBetween(1, 10),  // in minutes
        ];
    }
}
