<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('allergen_name'); // e.g., Penicillin, Peanuts, Pollen
            $table->enum('severity', ['mild', 'moderate', 'severe', 'life_threatening']);
            $table->text('reaction_description')->nullable();
            $table->date('first_noticed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergies');
    }
};