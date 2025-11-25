<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'subject_id',
        'title',
        'category',
        'class_level',
        'location',
        'day_of_week',
        'start_time',
        'duration_minutes',
        'student_count',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'duration_minutes' => 'integer',
        'student_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ScheduleSession::class, 'schedule_template_id');
    }
}
