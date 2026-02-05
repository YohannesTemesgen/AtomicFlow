<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Client;
use App\Models\Stage;

class LeadsTableSeeder extends Seeder
{
    public function run(): void
    {
        $stages = Stage::all()->keyBy('id');
        $clients = Client::all();

        if ($clients->isEmpty() || $stages->isEmpty()) {
            return;
        }

        $leads = [
            [
                'title' => 'Website Redesign Project',
                'description' => 'Complete redesign of company website with modern UI/UX',
                'value' => 25000.00,
                'priority' => 'high',
                'status' => 'active',
                'expected_close_date' => now()->addDays(30),
                'client_id' => $clients->where('name', 'Digital Dynamics')->first()->id,
                'stage_id' => $stages->where('position', 1)->first()->id ?? $stages->first()->id,
                'user_id' => 1,
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Native iOS and Android app for e-commerce platform',
                'value' => 50000.00,
                'priority' => 'high',
                'status' => 'active',
                'expected_close_date' => now()->addDays(45),
                'client_id' => $clients->where('name', 'Digital Dynamics')->first()->id,
                'stage_id' => $stages->where('position', 2)->first()->id ?? $stages->first()->id,
                'user_id' => 1,
            ],
            [
                'title' => 'Security Audit',
                'description' => 'Comprehensive security audit and penetration testing',
                'value' => 20000.00,
                'priority' => 'high',
                'status' => 'active',
                'expected_close_date' => now()->addDays(15),
                'client_id' => $clients->where('name', 'Innovation Labs')->first()->id,
                'stage_id' => $stages->where('position', 1)->first()->id ?? $stages->first()->id,
                'user_id' => 1,
            ],
            [
                'title' => 'API Integration',
                'description' => 'Integrate third-party APIs with existing systems',
                'value' => 12000.00,
                'priority' => 'medium',
                'status' => 'active',
                'expected_close_date' => now()->addDays(25),
                'client_id' => $clients->where('name', 'Innovation Labs')->first()->id,
                'stage_id' => $stages->where('position', 2)->first()->id ?? $stages->first()->id,
                'user_id' => 1,
            ],
            [
                'title' => 'Cloud Migration Services',
                'description' => 'Migrate infrastructure to AWS cloud platform',
                'value' => 75000.00,
                'priority' => 'medium',
                'status' => 'active',
                'expected_close_date' => now()->addDays(60),
                'client_id' => $clients->where('name', 'Acme Corporation')->first()->id,
                'stage_id' => $stages->where('position', 3)->first()->id ?? $stages->first()->id,
                'user_id' => 1,
            ],
            [
                'title' => 'Marketing Automation',
                'description' => 'Implement HubSpot marketing automation system',
                'value' => 15000.00,
                'priority' => 'low',
                'status' => 'active',
                'expected_close_date' => now()->addDays(20),
                'client_id' => $clients->where('name', 'Acme Corporation')->first()->id,
                'stage_id' => $stages->where('position', 4)->first()->id ?? $stages->first()->id,
                'user_id' => 1,
            ],
        ];

        foreach ($leads as $lead) {
            Lead::create($lead);
        }
    }
}
