<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Counter>
 */
class CounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Counter ' . $this->faker->unique()->numberBetween(1, 5),
            'service_id' => $this->faker
                ->randomElement(
                    Service::pluck('id')->toArray()
                ),
        ];
    }
}
