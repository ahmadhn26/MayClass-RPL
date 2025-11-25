<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoTutorSeeder extends Seeder
{
    public function run(): void
    {
        $tutor = User::updateOrCreate(
            ['email' => 'tentor@gmail.com'],
            [
                'name' => 'Tentor Demo MayClass',
                'username' => 'tentor_demo',
                'email' => 'tentor@gmail.com',
                'password' => Hash::make('gatau123'),
                'role' => 'tutor',
                'phone' => '0812-0000-1234',
                'gender' => 'other',
                'student_id' => null,
                'address' => 'Jl. Ilmu Pendidikan No. 10, Bandung',
            ]
        );

        $tutor?->tutorProfile()->updateOrCreate(
            ['user_id' => $tutor->id],
            [
                'slug' => Str::slug($tutor->name) ?: 'tutor-demo-mayclass',
                'headline' => 'Tentor Sains & Matematika',
                'bio' => 'Berpengalaman mendampingi siswa menyiapkan ujian masuk perguruan tinggi dan Olimpiade Sains dengan pendekatan belajar adaptif.',
                'specializations' => 'Matematika SMA',
                'education' => 'S1 Pendidikan Matematika',
                'experience_years' => 5,
                'students_taught' => 128,
                'hours_taught' => 860,
                'rating' => 4.8,
                'certifications' => [
                    'Sertifikasi Pengajar Profesional 2023',
                    'Pelatihan Kurikulum Merdeka',
                ],
            ]
        );
    }
}
