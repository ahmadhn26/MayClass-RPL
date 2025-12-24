<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'user_id',
        'schedule_session_id',
        'type',
        'subject',
        'status',
        'attempts',
        'last_attempt_at',
        'provider_message_id',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'last_attempt_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheduleSession(): BelongsTo
    {
        return $this->belongsTo(ScheduleSession::class);
    }

    /**
     * Check if email was already successfully sent.
     */
    public function wasSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if we should retry this email.
     */
    public function shouldRetry(): bool
    {
        return $this->status !== 'sent' && $this->attempts < 3;
    }
}
