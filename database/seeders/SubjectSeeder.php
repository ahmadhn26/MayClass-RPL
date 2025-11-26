<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

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
            ['name' => 'Bahasa Inggris', 'level' => 'SD', 'description' => 'Pelajaran bahasa Inggris untuk SD'],
            
            // SMP Level
            ['name' => 'Matematika', 'level' => 'SMP', 'description' => 'Pelajaran matematika untuk SMP'],
            ['name' => 'Bahasa Inggris', 'level' => 'SMP', 'description' => 'Pelajaran bahasa Inggris untuk SMP'],
            ['name' => 'IPA', 'level' => 'SMP', 'description' => 'Ilmu Pengetahuan Alam untuk SMP'],

            // SMA Level
            ['name' => 'Matematika', 'level' => 'SMA', 'description' => 'Pelajaran matematika untuk SMA'],
            ['name' => 'Bahasa Inggris', 'level' => 'SMA', 'description' => 'Pelajaran bahasa Inggris untuk SMA'],
            ['name' => 'Fisika', 'level' => 'SMA', 'description' => 'Pelajaran fisika untuk SMA'],
            ['name' => 'Kimia', 'level' => 'SMA', 'description' => 'Pelajaran kimia untuk SMA'],
        ];

        // Hapus record di DB yang tidak ada di $subjects
        $existing = Subject::all();
        foreach ($existing as $ex) {
            $found = collect($subjects)->contains(function ($s) use ($ex) {
                return $s['name'] === $ex->name && $s['level'] === $ex->level;
            });

            if (! $found) {
                // kalau ada file path di model, hapus file di storage juga (sesuaikan nama kolom)
                if (isset($ex->file_path) && $ex->file_path) {
                    Storage::delete($ex->file_path);
                }
                $ex->delete();
            }
        }

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['name' => $subject['name'], 'level' => $subject['level']],
                ['description' => $subject['description']]
            );
        }
    }
}
