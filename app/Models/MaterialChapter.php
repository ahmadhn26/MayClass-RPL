<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'title',
        'description',
        'position',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
