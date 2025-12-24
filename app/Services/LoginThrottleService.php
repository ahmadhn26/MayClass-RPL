<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mengelola login throttling dan account locking
 * Melindungi aplikasi dari serangan brute force
 */
class LoginThrottleService
{
    /**
     * Jumlah maksimal percobaan login gagal sebelum akun dikunci
     */
    protected const MAX_ATTEMPTS = 5;

    /**
     * Durasi lock dasar dalam menit
     */
    protected const BASE_LOCK_DURATION = 10;

    /**
     * Increment durasi lock untuk setiap lock berulang (menit)
     */
    protected const LOCK_INCREMENT = 5;

    /**
     * Durasi maksimal lock dalam menit
     */
    protected const MAX_LOCK_DURATION = 30;

    /**
     * Cek apakah akun sedang dikunci
     */
    public function isLocked(User $user): bool
    {
        if (!$user->locked_until) {
            return false;
        }

        return now()->lt($user->locked_until);
    }

    /**
     * Dapatkan waktu tersisa lock dalam menit
     */
    public function getRemainingLockMinutes(User $user): int
    {
        if (!$this->isLocked($user)) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($user->locked_until) / 60);
    }

    /**
     * Dapatkan waktu tersisa lock dalam detik (untuk countdown)
     */
    public function getRemainingLockSeconds(User $user): int
    {
        if (!$this->isLocked($user)) {
            return 0;
        }

        return (int) now()->diffInSeconds($user->locked_until);
    }

    /**
     * Catat percobaan login gagal
     * Lock akun jika sudah mencapai batas maksimal
     */
    public function recordFailedAttempt(User $user): void
    {
        $attempts = $user->failed_login_attempts + 1;

        $updateData = [
            'failed_login_attempts' => $attempts,
            'last_failed_login_at' => now(),
        ];

        // Lock akun jika sudah mencapai batas
        if ($attempts >= self::MAX_ATTEMPTS) {
            $lockDuration = $this->calculateLockDuration($user);
            $updateData['locked_until'] = now()->addMinutes($lockDuration);

            Log::warning('Account locked due to too many failed login attempts.', [
                'user_id' => $user->id,
                'username' => $user->username,
                'attempts' => $attempts,
                'locked_until' => $updateData['locked_until'],
                'lock_duration_minutes' => $lockDuration,
                'ip' => request()->ip(),
            ]);
        }

        $user->update($updateData);
    }

    /**
     * Reset counter percobaan login gagal (dipanggil saat login berhasil)
     */
    public function resetAttempts(User $user): void
    {
        $user->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_failed_login_at' => null,
        ]);
    }

    /**
     * Hitung durasi lock berdasarkan histori lock sebelumnya
     * Durasi meningkat progresif untuk repeated offenders
     */
    protected function calculateLockDuration(User $user): int
    {
        // Jika pernah di-lock sebelumnya dalam 24 jam terakhir, tambah durasi
        $recentLockCount = 0;

        if ($user->last_failed_login_at && $user->last_failed_login_at->gt(now()->subHours(24))) {
            // Estimasi berapa kali user sudah di-lock berdasarkan pattern
            $recentLockCount = (int) floor($user->failed_login_attempts / self::MAX_ATTEMPTS);
        }

        $duration = self::BASE_LOCK_DURATION + ($recentLockCount * self::LOCK_INCREMENT);

        return min($duration, self::MAX_LOCK_DURATION);
    }

    /**
     * Dapatkan jumlah percobaan yang tersisa sebelum akun dikunci
     */
    public function getRemainingAttempts(User $user): int
    {
        return max(0, self::MAX_ATTEMPTS - $user->failed_login_attempts);
    }

    /**
     * Cek apakah perlu menampilkan warning (sudah 3+ percobaan gagal)
     */
    public function shouldShowWarning(User $user): bool
    {
        return $user->failed_login_attempts >= 3 && $user->failed_login_attempts < self::MAX_ATTEMPTS;
    }
}
