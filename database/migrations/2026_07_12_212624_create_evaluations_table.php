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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('offering_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('evaluator_role', ['user', 'coffeeshop']);
            $table->string('extraction_method', 60)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->json('descriptive');
            $table->json('affective')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
