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
        Schema::create('offferings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('coffee_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('evaluation_count')->default(0);
            $table->unsignedInteger('defective_evaluation_count')->default(0);
            $table->json('consensus')->nullable();
            $table->decimal('concordance', 4, 3)->nullable();
            $table->enum('concordance_level', ['low', 'acceptable', 'good', 'excellent'])->nullable();
            $table->enum('verification_status', ['provisional', 'verified'])->default('provisional');
            $table->timestamps();

            $table->unique(['location_id', 'coffee_id'], 'uq_location_coffee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offferings');
    }
};
