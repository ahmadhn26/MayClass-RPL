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

    // Accessor untuk mendapatkan URL foto yang benar
    public function getPhotoUrlAttribute(): string
    {
        if (empty($this->photo_path)) {
            return '';
        }

        // Jika sudah URL lengkap
        if (str_starts_with($this->photo_path, 'http')) {
            return $this->photo_path;
        }

        // Jika path baru (uploads/)
        if (str_starts_with($this->photo_path, 'uploads/')) {
            return asset($this->photo_path);
        }

        // Path lama (documentations/xxx.jpg) - sekarang file sudah dicopy ke uploads/documentations/
        // Cek apakah file ada di public/uploads/documentations/
        if (str_starts_with($this->photo_path, 'documentations/')) {
            $newPath = 'uploads/' . $this->photo_path;
            if (file_exists(public_path($newPath))) {
                return asset($newPath);
            }
        }

        // Fallback ke storage symlink
        return asset('storage/' . $this->photo_path);
    }
}
