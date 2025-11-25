<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PackageSeeder::class,
            SubjectSeeder::class,
            MaterialSeeder::class,
            QuizSeeder::class,
            DemoAdminSeeder::class,
            ScheduleSeeder::class,
            DemoStudentSeeder::class,
            StudentSeeder::class,
            DemoOrderSeeder::class,
        ]);
    }
}
