<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('board_fields');
        Schema::create('board_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_type_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('label');
            $table->string('type')->default('text');
            $table->string('placeholder')->nullable();
            $table->text('default_value')->nullable();
            $table->json('options')->nullable();
            $table->json('validation_rules')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['board_type_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_fields');
    }
};
