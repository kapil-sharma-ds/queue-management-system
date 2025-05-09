<?php

namespace Database\Seeders;

use App\Models\QueueRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QueueRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QueueRule::factory()->count(2)->create();
    }
}
