<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientsTableSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Acme Corporation',
                'email' => 'contact@acme.com',
                'phone' => '+1-555-0100',
                'location' => 'New York, USA',
                'project_category' => 'Web Development',
                'notes' => 'Large enterprise client with multiple projects',
                'user_id' => 1,
            ],
            [
                'name' => 'Digital Dynamics',
                'email' => 'info@digitaldynamics.com',
                'phone' => '+1-555-0101',
                'location' => 'San Francisco, USA',
                'project_category' => 'Mobile Development',
                'notes' => 'Tech startup focusing on mobile solutions',
                'user_id' => 1,
            ],
            [
                'name' => 'Innovation Labs',
                'email' => 'hello@innovationlabs.com',
                'phone' => '+1-555-0102',
                'location' => 'Austin, USA',
                'project_category' => 'Software Development',
                'notes' => 'R&D company with cutting-edge projects',
                'user_id' => 1,
            ],
            [
                'name' => 'Global Enterprises',
                'email' => 'business@globalent.com',
                'phone' => '+1-555-0103',
                'location' => 'Chicago, USA',
                'project_category' => 'Enterprise Solutions',
                'notes' => 'Fortune 500 company requiring enterprise-level solutions',
                'user_id' => 1,
            ],
            [
                'name' => 'Tech Solutions Inc',
                'email' => 'sales@techsolutions.com',
                'phone' => '+1-555-0104',
                'location' => 'Seattle, USA',
                'project_category' => 'IT Consulting',
                'notes' => 'IT consulting and managed services provider',
                'user_id' => 1,
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
