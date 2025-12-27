<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthMetricsTable extends Migration
{
    public function up()
    {
        Schema::create('health_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('metric_type', [
                'blood_pressure', 
                'blood_sugar', 
                'weight', 
                'temperature', 
                'bmi',
                'heart_rate'
            ]);
            $table->decimal('value', 8, 2);
            $table->json('additional_data')->nullable();
            $table->string('unit');
            $table->timestamp('recorded_at');
            $table->timestamps();
            
            $table->index(['user_id', 'metric_type']);
            $table->index('recorded_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_metrics');
    }
}