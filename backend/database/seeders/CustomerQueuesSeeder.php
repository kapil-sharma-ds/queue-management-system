<?php

namespace Database\Seeders;

use App\Models\CustomerQueue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerQueuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate customer queue entries using the factory
        CustomerQueue::factory()->count(2)->create();
    }
}
