<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Stage;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        // Create stages
        $stages = [
            ['name' => 'Lead', 'slug' => 'lead', 'position' => 1, 'color' => '#3B82F6', 'description' => 'Initial contact'],
            ['name' => 'Qualified', 'slug' => 'qualified', 'position' => 2, 'color' => '#10B981', 'description' => 'Lead qualified'],
            ['name' => 'Proposal', 'slug' => 'proposal', 'position' => 3, 'color' => '#F59E0B', 'description' => 'Proposal sent'],
            ['name' => 'Negotiation', 'slug' => 'negotiation', 'position' => 4, 'color' => '#EF4444', 'description' => 'In negotiation'],
            ['name' => 'Closed Won', 'slug' => 'completed', 'position' => 5, 'color' => '#8B5CF6', 'description' => 'Deal closed'],
        ];

        foreach ($stages as $stageData) {
            Stage::firstOrCreate(['slug' => $stageData['slug']], $stageData);
        }

        // Create demo clients
        $clients = [
            ['name' => 'Acme Corporation', 'email' => 'contact@acme.com', 'phone' => '+1-555-0101', 'user_id' => $user->id],
            ['name' => 'Tech Solutions Inc', 'email' => 'info@techsolutions.com', 'phone' => '+1-555-0102', 'user_id' => $user->id],
            ['name' => 'Global Enterprises', 'email' => 'hello@global.com', 'phone' => '+1-555-0103', 'user_id' => $user->id],
            ['name' => 'Innovation Labs', 'email' => 'contact@innovationlabs.com', 'phone' => '+1-555-0104', 'user_id' => $user->id],
            ['name' => 'Digital Dynamics', 'email' => 'sales@digitaldynamics.com', 'phone' => '+1-555-0105', 'user_id' => $user->id],
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(['email' => $clientData['email']], $clientData);
        }

        // Get created clients
        $createdClients = Client::all();

        // Create demo leads
        $leads = [
            [
                'title' => 'Website Redesign Project',
                'description' => 'Complete redesign of company website with modern UI/UX',
                'value' => 25000.00,
                'priority' => 'high',
                'status' => 'active',
                'expected_close_date' => now()->addDays(30),
                'stage_slug' => 'lead',
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Native iOS and Android app for e-commerce platform',
                'value' => 50000.00,
                'priority' => 'high',
                'status' => 'active',
                'expected_close_date' => now()->addDays(45),
                'stage_slug' => 'qualified',
            ],
            [
                'title' => 'Cloud Migration Services',
                'description' => 'Migrate infrastructure to AWS cloud platform',
                'value' => 75000.00,
                'priority' => 'medium',
                'status' => 'active',
                'expected_close_date' => now()->addDays(60),
                'stage_slug' => 'proposal',
            ],
            [
                'title' => 'Marketing Automation',
                'description' => 'Implement HubSpot marketing automation system',
                'value' => 15000.00,
                'priority' => 'low',
                'status' => 'active',
                'expected_close_date' => now()->addDays(20),
                'stage_slug' => 'negotiation',
            ],
            [
                'title' => 'Data Analytics Platform',
                'description' => 'Custom analytics dashboard for business intelligence',
                'value' => 35000.00,
                'priority' => 'medium',
                'status' => 'won',
                'expected_close_date' => now()->subDays(10),
                'stage_slug' => 'completed',
            ],
            [
                'title' => 'Security Audit',
                'description' => 'Comprehensive security audit and penetration testing',
                'value' => 20000.00,
                'priority' => 'high',
                'status' => 'active',
                'expected_close_date' => now()->addDays(15),
                'stage_slug' => 'lead',
            ],
            [
                'title' => 'API Integration',
                'description' => 'Integrate third-party APIs with existing systems',
                'value' => 12000.00,
                'priority' => 'medium',
                'status' => 'active',
                'expected_close_date' => now()->addDays(25),
                'stage_slug' => 'qualified',
            ],
            [
                'title' => 'CRM Implementation',
                'description' => 'Deploy Salesforce CRM for sales team',
                'value' => 40000.00,
                'priority' => 'high',
                'status' => 'won',
                'expected_close_date' => now()->subDays(5),
                'stage_slug' => 'completed',
            ],
        ];

        foreach ($leads as $leadData) {
            $stage = Stage::where('slug', $leadData['stage_slug'])->first();
            $client = $createdClients->random();
            
            Lead::firstOrCreate([
                'title' => $leadData['title'],
                'user_id' => $user->id,
            ], [
                'description' => $leadData['description'],
                'value' => $leadData['value'],
                'priority' => $leadData['priority'],
                'status' => $leadData['status'],
                'expected_close_date' => $leadData['expected_close_date'],
                'client_id' => $client->id,
                'stage_id' => $stage->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
