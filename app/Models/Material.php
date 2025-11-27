<?php

namespace App\Models;

use App\Support\ImageRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'package_id',
        'subject_id',
        'title',
        'level',
        'summary',
        'thumbnail_url',
        'thumbnail_url',
        'resource_path',
        'quiz_urls',
    ];

    protected $casts = [
        'resource_path' => 'array',
        'quiz_urls' => 'array',
    ];

    public function objectives(): HasMany
    {
        return $this->hasMany(MaterialObjective::class)->orderBy('position');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(MaterialChapter::class)->orderBy('position');
    }

    public function getThumbnailAssetAttribute(): string
    {
        $key = $this->attributes['thumbnail_url'] ?? '';

        return ImageRepository::url("materials.$key");
    }

    public function getResourceUrlAttribute(): array
    {
        return $this->attributes['resource_path']
            ? json_decode($this->attributes['resource_path'], true)
            : [];
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)->withTimestamps();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}

