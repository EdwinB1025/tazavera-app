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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(table: 'users', column: 'id', indexName: 'users_id_locations');
            $table->string('name', length: 150);
            $table->string('description');
            $table->string('phone', length: 25);
            $table->string('email');
            $table->string('web');
            $table->string('social');
            $table->string('address');
            $table->decimal('latitud', total: 10, places: 8);
            $table->decimal('longitud', total: 11, places: 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
