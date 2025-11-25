<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        // Tambahkan kolom username kalau belum ada
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('email');
            }
        });

        if (!Schema::hasColumn('users', 'username')) {
            return;
        }

        // Isi username otomatis untuk data lama
        DB::table('users')->orderBy('id')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                if (!empty($user->username)) {
                    continue;
                }

                $base = Str::slug($user->name) ?: 'mayclass-user-' . $user->id;
                $candidate = $base;
                $suffix = 1;

                while (
                    DB::table('users')
                        ->where('username', $candidate)
                        ->where('id', '!=', $user->id)
                        ->exists()
                ) {
                    $candidate = $base . '-' . $suffix;
                    $suffix++;
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['username' => $candidate]);
            }
        });

        // Tambahkan unique index (dengan pengecekan manual)
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username', 'users_username_unique');
            });
        } catch (\Throwable $e) {
            // Jika index sudah ada, abaikan
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        // Drop kolom dengan aman
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique('users_username_unique');
            } catch (\Throwable $e) {
                // Abaikan jika index tidak ada
            }

            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
};
