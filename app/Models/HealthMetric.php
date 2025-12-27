<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'metric_type',
        'value',
        'additional_data',
        'unit',
        'recorded_at'
    ];

    protected $casts = [
        'additional_data' => 'array',
        'recorded_at' => 'datetime',
        'value' => 'float' // Changed decimal to float for easier JS handling
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // FIXED SCOPE: Now correctly filters by user inside the MAX calculation
    public function scopeLatestForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->whereIn('id', function($subquery) use ($userId) {
                $subquery->selectRaw('MAX(id)')
                    ->from('health_metrics')
                    ->where('user_id', $userId) // CRITICAL: Must filter here too
                    ->groupBy('metric_type');
            });
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('recorded_at', [$startDate, $endDate]);
    }
}