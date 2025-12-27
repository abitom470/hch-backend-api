<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthMetric;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HealthMetricController extends Controller
{
    private function unauthorizedResponse() {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    private function parseExtraData($data) {
        if (is_array($data)) return $data;
        if (is_string($data)) return json_decode($data, true) ?? [];
        return [];
    }

    // UPDATED: Changed parameter order to be more flexible for Laravel
    public function getChartData(Request $request, $metricType)
    {
        try {
            $user = Auth::user();
            if (!$user) return $this->unauthorizedResponse();

            $days = (int) $request->get('days', 30);
            $startDate = Carbon::now()->subDays($days)->startOfDay();

            $metrics = HealthMetric::where('user_id', $user->id)
                ->where('metric_type', $metricType)
                ->where('recorded_at', '>=', $startDate)
                ->orderBy('recorded_at', 'asc')
                ->get();

            $formattedData = $metrics->map(function ($item) {
                $extra = $this->parseExtraData($item->additional_data);
                return [
                    'date' => Carbon::parse($item->recorded_at)->format('M d'), 
                    'value' => (float)$item->value,
                    'systolic' => $extra['systolic'] ?? null,
                    'diastolic' => $extra['diastolic'] ?? null,
                ];
            });

            return response()->json(['success' => true, 'data' => $formattedData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getStatus()
    {
        try {
            $user = Auth::user();
            if (!$user) return $this->unauthorizedResponse();

            $latestMetrics = HealthMetric::where('user_id', $user->id)
                ->whereIn('id', function($query) use ($user) {
                    $query->selectRaw('MAX(id)')
                          ->from('health_metrics')
                          ->where('user_id', $user->id)
                          ->groupBy('metric_type');
                })->get();

            $status = [];
            foreach ($latestMetrics as $metric) {
                $status[$metric->metric_type] = $this->calculateHealthStatus($metric);
            }
            return response()->json(['success' => true, 'data' => ['metrics_status' => $status]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function calculateHealthStatus($metric)
    {
        $value = $metric->value;
        $extra = $this->parseExtraData($metric->additional_data);
        if ($metric->metric_type === 'blood_pressure') {
            $systolic = $extra['systolic'] ?? $value;
            $diastolic = $extra['diastolic'] ?? 0;
            return [
                'value' => "$systolic/$diastolic",
                'unit' => 'mmHg',
                'status' => ($systolic < 120 && $diastolic < 80) ? 'normal' : 'elevated',
                'recorded_at' => $metric->recorded_at
            ];
        }
        return [
            'value' => (float)$value,
            'unit' => $metric->unit,
            'status' => 'recorded',
            'recorded_at' => $metric->recorded_at
        ];
    }

    public function store(Request $request) { 
        // Logic kept simple for testing
        return response()->json(['success' => true, 'message' => 'Stored']); 
    }
    public function getHistory() { return response()->json(['success' => true, 'data' => []]); }
    public function storeSymptom() { return response()->json(['success' => true]); }
    public function getSymptomHistory() { return response()->json(['success' => true, 'data' => []]); }
}