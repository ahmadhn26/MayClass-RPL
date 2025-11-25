<?php

namespace App\Models;

use App\Support\ImageRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'package_id',
        'subject_id',
        'class_level',
        'title',
        'summary',
        'thumbnail_url',
        'duration_label',
        'question_count',
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(QuizLevel::class)->orderBy('position');
    }

    public function takeaways(): HasMany
    {
        return $this->hasMany(QuizTakeaway::class)->orderBy('position');
    }

    public function getThumbnailAssetAttribute(): string
    {
        $key = $this->attributes['thumbnail_url'] ?? '';

        return ImageRepository::url("quizzes.$key");
    }

    public function getLinkAttribute(): ?string
    {
        return $this->attributes['link_url'] ?? null;
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}

