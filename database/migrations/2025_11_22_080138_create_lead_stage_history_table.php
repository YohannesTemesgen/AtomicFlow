<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('lead_stage_histories');
        Schema::create('lead_stage_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_stage_id')->nullable()->constrained('stages')->onDelete('set null');
            $table->foreignId('to_stage_id')->constrained('stages')->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('transitioned_at');
            $table->timestamps();
            
            $table->index('lead_id');
            $table->index('from_stage_id');
            $table->index('to_stage_id');
            $table->index('user_id');
            $table->index('transitioned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_stage_histories');
    }
};
