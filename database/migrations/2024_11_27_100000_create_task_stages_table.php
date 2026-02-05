<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('position')->default(0);
            $table->string('color')->default('#3b82f6');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default stages
        DB::table('task_stages')->insert([
            ['name' => 'Backlog', 'slug' => 'backlog', 'position' => 1, 'color' => '#6366f1', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'To-Do', 'slug' => 'to-do', 'position' => 2, 'color' => '#3b82f6', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Progress', 'slug' => 'in-progress', 'position' => 3, 'color' => '#f59e0b', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Review', 'slug' => 'review', 'position' => 4, 'color' => '#8b5cf6', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Completed', 'slug' => 'completed', 'position' => 5, 'color' => '#22c55e', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('task_stages');
    }
};
