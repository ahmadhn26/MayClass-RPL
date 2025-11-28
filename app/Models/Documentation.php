<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    protected $fillable = [
        'photo_path',
        'activity_date',
        'description',
        'week_number',
        'year',
        'is_active',
        'order',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Scope untuk ambil dokumentasi minggu ini
    public function scopeThisWeek($query)
    {
        $weekNumber = now()->weekOfYear;
        $year = now()->year;

        return $query->where('year', $year)
            ->where('week_number', $weekNumber)
            ->where('is_active', true);
    }

    // Scope untuk urutan terbaru
    public function scopeLatestOrder($query)
    {
        return $query->orderBy('order', 'desc')->orderBy('created_at', 'desc');
    }
}
