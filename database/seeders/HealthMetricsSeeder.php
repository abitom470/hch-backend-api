<?php

namespace Database\Seeders;

use App\Models\HealthMetric;
use App\Models\SymptomLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HealthMetricsSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // Get first user for sample data

        if (!$user) {
            return;
        }

        // Sample health metrics for the past 30 days
        $metrics = [
            // Blood Pressure
            [
                'metric_type' => 'blood_pressure',
                'value' => 120,
                'additional_data' => ['systolic' => 120, 'diastolic' => 80],
                'unit' => 'mmHg',
                'recorded_at' => Carbon::now()->subDays(rand(0, 30))
            ],
            [
                'metric_type' => 'blood_pressure',
                'value' => 118,
                'additional_data' => ['systolic' => 118, 'diastolic' => 78],
                'unit' => 'mmHg',
                'recorded_at' => Carbon::now()->subDays(rand(0, 30))
            ],
            
            // Blood Sugar
            [
                'metric_type' => 'blood_sugar',
                'value' => 95,
                'additional_data' => null,
                'unit' => 'mg/dL',
                'recorded_at' => Carbon::now()->subDays(rand(0, 30))
            ],
            
            // Weight
            [
                'metric_type' => 'weight',
                'value' => 70,
                'additional_data' => null,
                'unit' => 'kg',
                'recorded_at' => Carbon::now()->subDays(rand(0, 30))
            ],
            
            // Temperature
            [
                'metric_type' => 'temperature',
                'value' => 36.6,
                'additional_data' => null,
                'unit' => '°C',
                'recorded_at' => Carbon::now()->subDays(rand(0, 30))
            ]
        ];

        foreach ($metrics as $metric) {
            HealthMetric::create(array_merge($metric, ['user_id' => $user->id]));
        }

        // Sample symptoms
        $symptoms = [
            [
                'symptom' => 'Headache',
                'description' => 'Mild headache in the forehead',
                'severity' => 'mild',
                'recorded_at' => Carbon::now()->subDays(2)
            ],
            [
                'symptom' => 'Fatigue',
                'description' => 'Feeling tired throughout the day',
                'severity' => 'moderate',
                'recorded_at' => Carbon::now()->subDays(5)
            ]
        ];

        foreach ($symptoms as $symptom) {
            SymptomLog::create(array_merge($symptom, ['user_id' => $user->id]));
        }
    }
}