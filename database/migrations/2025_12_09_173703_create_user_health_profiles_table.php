<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_health_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->string('blood_type', 10)->nullable();
            $table->json('known_conditions')->nullable(); // chronic conditions
            $table->json('lifestyle_factors')->nullable(); // smoking, alcohol, exercise
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_health_profiles');
    }
};