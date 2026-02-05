<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('board_stages');
        Schema::create('board_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_type_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('color')->default('#6B7280');
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['board_type_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_stages');
    }
};
