<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Counter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerQueue>
 */
class CustomerQueueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusOptions = ['waiting', 'called', 'skipped', 'served'];

        $joinedAt = $this->faker->dateTimeBetween('-1 days', 'now');
        $calledAt = $this->faker->optional()->dateTimeBetween($joinedAt, 'now');
        $servedAt = $this->faker->optional()->dateTimeBetween($calledAt ?: $joinedAt, 'now');

        return [
            'customer_name' => $this->faker->name(),
            'service_id' => $this->faker
                ->randomElement(
                    Service::pluck('id')->toArray()
                ),
            'counter_id' => $this->faker
                ->randomElement(
                    Counter::pluck('id')->toArray()
                ),
            'queue_number' => $this->faker->unique()->numberBetween(1, 999),
            'status' => $this->faker->randomElement($statusOptions),
            'joined_at' => $joinedAt,
            'called_at' => $calledAt,
            'served_at' => $servedAt,
        ];
    }
}
