<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Billing', 'description' => 'Handle billing inquiries and payments.'],
            ['name' => 'Customer Support', 'description' => 'Assist customers with general queries.'],
            ['name' => 'Technical Support', 'description' => 'Resolve technical issues.'],
            ['name' => 'Account Opening', 'description' => 'Support for new account creation.'],
            ['name' => 'Information Desk', 'description' => 'Provide general information to visitors.'],
        ];

        foreach ($services as $service) {
            Service::factory()->create($service);
        }
    }
}

