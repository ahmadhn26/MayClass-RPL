<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizItem extends Model
{
    protected $fillable = ['quiz_id', 'name', 'description', 'link', 'position'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('position', function ($query) {
            $query->orderBy('position');
        });
    }
}
