<?php

namespace App\Support;

use App\Models\User;

class StudentIdGenerator
{
    public static function next(): string
    {
        do {
            $id = 'MC-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (User::where('student_id', $id)->exists());

        return $id;
    }
}
