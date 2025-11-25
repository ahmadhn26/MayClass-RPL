<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * 1. Arahkan user ke halaman login Google
     * Menangkap parameter ?mode=register atau ?mode=login
     */
    public function redirectToGoogle(Request $request)
    {
        // Default mode adalah 'login' jika tidak ada parameter
        $mode = $request->query('mode', 'login');

        // Simpan mode ke session untuk diingat saat callback nanti
        session(['google_auth_mode' => $mode]);

        return Socialite::driver('google')->redirect();
    }

    /**
     * 2. Handle balikan (callback) dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Ambil mode dari session (login/register)
            $mode = session('google_auth_mode', 'login');
            session()->forget('google_auth_mode'); // Bersihkan session

            // Cari user di database berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            // ==========================================================
            // SKENARIO A: USER BELUM ADA DI DATABASE
            // ==========================================================
            if (! $user) {
                
                // Jika mode LOGIN tapi user tidak ada -> TOLAK
                if ($mode === 'login') {
                    return redirect()
                        ->route('register')
                        ->with('error', 'Akun Google ini belum terdaftar. Silakan pilih menu Daftar (Register) terlebih dahulu.');
                }

                // Jika mode REGISTER -> BUAT USER BARU
                if ($mode === 'register') {
                    // Buat username aman (slug dari nama + string acak)
                    $username = Str::slug($googleUser->name) . '-' . Str::lower(Str::random(3));

                    // Buat User Baru
                    // Role diset 'visitor' agar sesuai dengan AuthController manual registration
                    $user = User::create([
                        'name'      => $googleUser->name,
                        'username'  => $username,
                        'email'     => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password'  => Hash::make(Str::random(16)), // Password acak
                        'is_active' => true,
                        'role'      => 'visitor', // Default role visitor (agar diarahkan ke packages)
                        'phone'     => null,
                        'gender'    => 'other',
                    ]);
                    
                    // Lanjut ke proses login di bawah...
                }
            }

            // ==========================================================
            // SKENARIO B: USER SUDAH ADA (LOGIN)
            // ==========================================================
            
            // Sinkronisasi google_id jika user lama belum punya
            if (! $user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }

            // Cek apakah akun diblokir/nonaktif (Keamanan Tambahan)
            if (Schema::hasColumn('users', 'is_active') && ! $user->is_active) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Akun Anda sedang dinonaktifkan. Hubungi admin.');
            }

            // Login User
            Auth::login($user);

            // ==========================================================
            // LOGIKA REDIRECT (Sesuai route di web.php)
            // ==========================================================
            $targetUrl = match ($user->role) {
                'tutor'   => route('tutor.dashboard'),   // Sesuai prefix 'tutor' di web.php
                'student' => route('student.dashboard'), // Sesuai prefix 'student' di web.php
                'admin'   => route('admin.dashboard'),   // Sesuai prefix 'admin' di web.php
                'visitor' => route('packages.index'),    // Sesuai route '/packages'
                default   => route('packages.index'),
            };

            // Jika tadi dari proses register, kirim pesan sukses
            if ($mode === 'register') {
                session()->flash('status', 'Pendaftaran Berhasil! Selamat datang.');
            }

            return redirect()->intended($targetUrl);

        } catch (\Exception $e) {
            // Jika error, kembalikan ke halaman asal sesuai mode
            $route = ($mode === 'register') ? 'register' : 'login';
            return redirect()
                ->route($route)
                ->with('error', 'Login Google Gagal: ' . $e->getMessage());
        }
    }
}