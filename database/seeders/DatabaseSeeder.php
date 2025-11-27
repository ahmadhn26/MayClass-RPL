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
        // Most seeders are disabled - database will start mostly empty
        // Only DemoAdminSeeder is active to create default admin account
        // Other data will be managed manually via Admin panel

        $this->call([
            DemoAdminSeeder::class,  // Active: Creates default admin user for login
        ]);
        // LandingContentSeeder::class,
        // PackageSeeder::class,
        // SubjectSeeder::class,
        // MaterialSeeder::class,
        // QuizSeeder::class,
        // ScheduleSeeder::class,
        // DemoStudentSeeder::class,
        // StudentSeeder::class,
        // DemoOrderSeeder::class,
    }
}