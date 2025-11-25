<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\StudentIdGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $students = [
            [
                'name' => 'Student One',
                'username' => 'student1',
                'email' => 'student1@mayclass.com',
                'password' => 'password123',
                'phone' => '081234567890',
                'gender' => 'male',
                'address' => 'Jakarta, Indonesia',
            ],
            [
                'name' => 'Student Two',
                'username' => 'student2',
                'email' => 'student2@mayclass.com',
                'password' => 'password123',
                'phone' => '081234567891',
                'gender' => 'female',
                'address' => 'Bandung, Indonesia',
            ],
            [
                'name' => 'Student Three',
                'username' => 'student3',
                'email' => 'student3@mayclass.com',
                'password' => 'password123',
                'phone' => '081234567892',
                'gender' => 'male',
                'address' => 'Surabaya, Indonesia',
            ],
        ];

        foreach ($students as $studentData) {
            $studentId = StudentIdGenerator::next();

            User::updateOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'username' => $studentData['username'],
                    'email' => $studentData['email'],
                    'password' => Hash::make($studentData['password']),
                    'role' => 'student',
                    'is_active' => true,
                    'student_id' => $studentId,
                    'phone' => $studentData['phone'],
                    'gender' => $studentData['gender'],
                    'address' => $studentData['address'],
                ]
            );
        }
    }
}