<?php

namespace Database\Seeders;

use App\Models\NotificationPreference;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationPreferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationPreference::factory()->count(2)->create();
    }
}
