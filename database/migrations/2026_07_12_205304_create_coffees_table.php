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
        Schema::create('coffees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('roastery', 150);
            $table->enum('roast_level', ['light', 'medium_light', 'medium', 'medium_dark', 'dark'])->default('medium');
            $table->json('extrinsics');
            $table->timestamps();

            $table->unique(['name', 'roastery_id'], 'uq_name_roastery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coffees');
    }
};
