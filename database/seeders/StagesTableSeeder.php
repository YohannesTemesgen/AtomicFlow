<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            [
                'name' => 'Contacted (Talked)',
                'slug' => 'contacted',
                'position' => 1,
                'color' => '#EF4444',
                'description' => 'Initial contact made with the lead',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Responded (Replied)',
                'slug' => 'responded',
                'position' => 2,
                'color' => '#F59E0B',
                'description' => 'Lead has responded to initial contact',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Negotiation (Agreed)',
                'slug' => 'negotiation',
                'position' => 3,
                'color' => '#10B981',
                'description' => 'In negotiation phase with the lead',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Active (Start Implementation)',
                'slug' => 'active',
                'position' => 4,
                'color' => '#3B82F6',
                'description' => 'Lead converted to active project',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Completed',
                'slug' => 'completed',
                'position' => 5,
                'color' => '#8B5CF6',
                'description' => 'Project completed successfully',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('stages')->insert($stages);
    }
}
