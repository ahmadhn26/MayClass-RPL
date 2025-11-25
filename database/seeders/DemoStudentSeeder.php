<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoStudentSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $students = [
  
        ];

        foreach ($students as $student) {
            $user = User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'username' => $student['username'],
                    'email' => $student['email'],
                    'password' => Hash::make('gatau123'),
                    'role' => 'student',
                    'phone' => $student['phone'],
                    'gender' => $student['gender'],
                    'student_id' => $student['student_id'],
                    'address' => $student['address'],
                    'remember_token' => Str::random(10),
                ]
            );

            if ($student['joined_at']) {
                $user->forceFill([
                    'created_at' => $student['joined_at'],
                    'updated_at' => $student['joined_at'],
                ])->save();
            }
        }
    }
}
