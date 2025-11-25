<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'schedule_template_id',
        'title',
        'category',
        'class_level',
        'location',
        'student_count',
        'mentor_name',
        'start_at',
        'duration_minutes',
        'is_highlight',
        'status',
        'cancelled_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'is_highlight' => 'boolean',
        'student_count' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ScheduleTemplate::class, 'schedule_template_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
