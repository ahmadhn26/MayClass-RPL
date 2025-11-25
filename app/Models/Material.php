<?php

namespace App\Models;

use App\Support\ImageRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'resource_path',
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

    public function getResourceUrlAttribute(): ?string
    {
        $path = $this->attributes['resource_path'] ?? null;

        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
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

