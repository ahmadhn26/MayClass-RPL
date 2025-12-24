<?php

namespace App\Providers;

use App\Models\User; // Masih dipertahankan, meskipun tidak digunakan di sini, mungkin digunakan di tempat lain.
use App\Support\Database\FallbackMySqlConnector;
use App\Support\StudentAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str; // Masih dipertahankan
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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        $this->guardSessionDriverFallback();
        // $this->ensureDemoAccounts(); <-- Dihapus
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

    // Metode ensureDemoAccounts() telah dihapus.
    // Metode ensureDemoUser() telah dihapus.

    private function shareStudentAccessState(): void
    {
        View::composer('student.*', function ($view): void {
            $user = Auth::user();

            $view->with('studentHasActivePackage', StudentAccess::hasActivePackage($user));
            $view->with('studentActiveEnrollment', StudentAccess::activeEnrollment($user));
        });
    }
}