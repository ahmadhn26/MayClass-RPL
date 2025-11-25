<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // SD Level
            ['name' => 'Matematika', 'level' => 'SD', 'description' => 'Pelajaran matematika dasar untuk SD'],
            ['name' => 'Bahasa Indonesia', 'level' => 'SD', 'description' => 'Pelajaran bahasa Indonesia untuk SD'],
            ['name' => 'IPA', 'level' => 'SD', 'description' => 'Ilmu Pengetahuan Alam untuk SD'],
            ['name' => 'IPS', 'level' => 'SD', 'description' => 'Ilmu Pengetahuan Sosial untuk SD'],

            // SMP Level
            ['name' => 'Matematika', 'level' => 'SMP', 'description' => 'Pelajaran matematika untuk SMP'],
            ['name' => 'Bahasa Indonesia', 'level' => 'SMP', 'description' => 'Pelajaran bahasa Indonesia untuk SMP'],
            ['name' => 'Bahasa Inggris', 'level' => 'SMP', 'description' => 'Pelajaran bahasa Inggris untuk SMP'],
            ['name' => 'IPA', 'level' => 'SMP', 'description' => 'Ilmu Pengetahuan Alam untuk SMP'],
            ['name' => 'IPS', 'level' => 'SMP', 'description' => 'Ilmu Pengetahuan Sosial untuk SMP'],

            // SMA Level
            ['name' => 'Matematika', 'level' => 'SMA', 'description' => 'Pelajaran matematika untuk SMA'],
            ['name' => 'Bahasa Indonesia', 'level' => 'SMA', 'description' => 'Pelajaran bahasa Indonesia untuk SMA'],
            ['name' => 'Bahasa Inggris', 'level' => 'SMA', 'description' => 'Pelajaran bahasa Inggris untuk SMA'],
            ['name' => 'Fisika', 'level' => 'SMA', 'description' => 'Pelajaran fisika untuk SMA'],
            ['name' => 'Kimia', 'level' => 'SMA', 'description' => 'Pelajaran kimia untuk SMA'],
            ['name' => 'Biologi', 'level' => 'SMA', 'description' => 'Pelajaran biologi untuk SMA'],
            ['name' => 'Sejarah', 'level' => 'SMA', 'description' => 'Pelajaran sejarah untuk SMA'],
            ['name' => 'Geografi', 'level' => 'SMA', 'description' => 'Pelajaran geografi untuk SMA'],
            ['name' => 'Ekonomi', 'level' => 'SMA', 'description' => 'Pelajaran ekonomi untuk SMA'],
            ['name' => 'Sosiologi', 'level' => 'SMA', 'description' => 'Pelajaran sosiologi untuk SMA'],
            ['name' => 'UTBK', 'level' => 'SMA', 'description' => 'Persiapan Ujian Tulis Berbasis Komputer'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
