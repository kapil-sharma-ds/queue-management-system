<?php

namespace Database\Seeders;

use App\Models\OperatingHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatingHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(0, 6) as $day) {
            OperatingHour::factory()->create([
                'day_of_week' => $day,
                'opens_at' => '09:00:00',
                'closes_at' => '17:00:00',
            ]);
        }
    }
}
