<?php

namespace App\Providers;

use App\Models\User;
use App\Support\Database\FallbackMySqlConnector;
use App\Support\StudentAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerDatabaseFallbackConnector();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->guardSessionDriverFallback();
        $this->ensureDemoAccounts();
        $this->shareStudentAccessState();
    }

    private function guardSessionDriverFallback(): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        $table = config('session.table', 'sessions');

        try {
            if (Schema::hasTable($table)) {
                return;
            }

            $this->activateFileSessionDriver('database_table_missing', $table);
        } catch (Throwable $exception) {
            $this->activateFileSessionDriver('database_check_failed', $table, $exception->getMessage());
        }
    }

    private function activateFileSessionDriver(string $reason, string $table, ?string $message = null): void
    {
        if (config('session.driver') === 'file') {
            return;
        }

        Config::set('session.driver', 'file');
        Session::setDefaultDriver('file');

        Log::warning('Falling back to file session driver for MayClass.', array_filter([
            'reason' => $reason,
            'table' => $table,
            'message' => $message,
        ]));
    }

    private function registerDatabaseFallbackConnector(): void
    {
        $this->app->extend('db.connector.mysql', function () {
            return new FallbackMySqlConnector();
        });

        $this->app->extend('db.connector.mariadb', function () {
            return new FallbackMySqlConnector();
        });
    }

    private function ensureDemoAccounts(): void
    {
        if (app()->environment('production')) {
            return;
        }

        try {
            if (! Schema::hasTable('users')) {
                return;
            }
        } catch (Throwable $exception) {
            Log::debug('Skipping demo account provisioning while schema unavailable.', [
                'message' => $exception->getMessage(),
            ]);

            return;
        }

        $tutor = $this->ensureDemoUser(
            'tentor_demo',
            'tentor@gmail.com',
            'gatau123',
            [
                'name' => 'Tentor Demo MayClass',
                'role' => 'tutor',
                'phone' => '0812-0000-1234',
                'gender' => 'other',
                'address' => 'Jl. Ilmu Pendidikan No. 10, Bandung',
            ]
        );

        if ($tutor && Schema::hasTable('tutor_profiles')) {
            $tutor->tutorProfile()->updateOrCreate(
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

        $this->ensureDemoUser(
            'admin_utama',
            'admin@gmail.com',
            'gatau123',
            [
                'name' => 'Admin Utama MayClass',
                'role' => 'admin',
                'phone' => '0812-7777-1234',
                'gender' => 'other',
                'address' => 'Jl. Pengelola Pendidikan No. 1, Bandung',
            ]
        );
    }

    private function ensureDemoUser(string $username, string $email, string $plainPassword, array $attributes): ?User
    {
        // Cek dulu apakah tabel users sudah ada (untuk menghindari error saat migrate fresh)
        if (!Schema::hasTable('users')) {
            return null;
        }

        $user = User::firstOrNew(['email' => $email]);

        // HAPUS 'is_active' => true DARI SINI
        $payload = array_merge([], $attributes, [
            'email' => $email,
        ]);

        // BARU CEK DI SINI: Kalau kolom is_active ada, baru set true
        if (Schema::hasColumn('users', 'is_active')) {
            $payload['is_active'] = true;
        }

        if (Schema::hasColumn('users', 'username')) {
            $payload['username'] = $username;
        }

        $user->fill($payload);

        if (! $user->exists || empty($user->password) || ! Hash::check($plainPassword, $user->password)) {
            $user->password = Hash::make($plainPassword);
        }

        $user->save();

        return $user->fresh();
    }
    
    private function shareStudentAccessState(): void
    {
        View::composer('student.*', function ($view): void {
            $user = Auth::user();

            $view->with('studentHasActivePackage', StudentAccess::hasActivePackage($user));
            $view->with('studentActiveEnrollment', StudentAccess::activeEnrollment($user));
        });
    }
}
