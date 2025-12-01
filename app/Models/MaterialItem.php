<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialItem extends Model
{
    protected $fillable = [
        'material_id',
        'name',
        'description',
        'link',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    /**
     * Get the material (folder) that owns this item.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Default ordering by position.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('position', function ($builder) {
            $builder->orderBy('position');
        });
    }
}
