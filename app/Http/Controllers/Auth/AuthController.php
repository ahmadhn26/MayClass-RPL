<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Database\Schema\Blueprint;
use PDOException;
use Throwable;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->to($this->homeRouteFor(Auth::user()));
        }

        return view('auth.index', [
            'mode' => 'login',
            'profile' => $request->session()->get('register.profile', []),
            'captcha' => $this->generateCaptchaChallenge($request),
        ]);
    }

    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect()->to($this->homeRouteFor(Auth::user()));
        }

        return view('auth.index', [
            'mode' => 'register',
            'profile' => $request->session()->get('register.profile', []),
            'captcha' => $this->generateCaptchaChallenge($request),
        ]);
    }

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->to($this->homeRouteFor(Auth::user()));
        }

        $whatsappConfig = config('mayclass.support.whatsapp', []);
        $contactName = $whatsappConfig['contact_name'] ?? 'Admin MayClass';
        $contactNumber = $whatsappConfig['number'] ?? null;
        $availability = $whatsappConfig['availability'] ?? __('Setiap hari');
        $prefilledMessage = $whatsappConfig['predefined_message'] ?? __('Halo Admin MayClass, saya lupa password akun MayClass.');

        $sanitizedNumber = $contactNumber ? preg_replace('/\D+/', '', $contactNumber) : null;
        $whatsappLink = $sanitizedNumber
            ? sprintf('https://wa.me/%s?text=%s', $sanitizedNumber, rawurlencode($prefilledMessage))
            : null;

        return view('auth.forgot-password', [
            'contactName' => $contactName,
            'contactNumber' => $contactNumber,
            'availability' => $availability,
            'whatsappLink' => $whatsappLink,
            'supportMessage' => $prefilledMessage,
        ]);
    }

    public function join(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login');
    }

    public function storeRegisterDetails(Request $request): RedirectResponse
    {
        $this->ensureUsernameSupport();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:4', 'max:50', Rule::unique(User::class, 'username')],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
        ]);

        unset($data['captcha_answer']);

        $request->session()->forget('register.captcha_answer');

        $request->session()->put('register.profile', $data);

        return redirect()->route('register.password');
    }

    public function showPasswordStep(Request $request)
    {
        if (Auth::check()) {
            return redirect()->to($this->homeRouteFor(Auth::user()));
        }

        $profile = $request->session()->get('register.profile');

        if (! $profile) {
            return redirect()->route('register')->with('status', __('Silakan lengkapi data diri terlebih dahulu.'));
        }

        return view('auth.register-password', [
            'profile' => $profile,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $this->ensureUsernameSupport();

        $profile = $request->session()->get('register.profile');

        if (! $profile) {
            return redirect()->route('register')->with('status', __('Silakan lengkapi data diri terlebih dahulu.'));
        }

        // ✅ Validasi data profile yang ada di session (tanpa recaptcha)
        $profileValidator = Validator::make($profile, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:4', 'max:50', Rule::unique(User::class, 'username')],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
        ]);

        if ($profileValidator->fails()) {
            return redirect()
                ->route('register')
                ->withErrors($profileValidator)
                ->withInput($profile);
        }

        // ✅ Validasi password + reCAPTCHA dari form password step
        $passwordData = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $secret = config('services.recaptcha.secret');

                    if (! $secret) {
                        Log::warning('Google reCAPTCHA secret key tidak dikonfigurasi.');
                        $fail(__('Konfigurasi reCAPTCHA belum benar. Hubungi admin.'));

                        return;
                    }

                    try {
                        $response = Http::asForm()->post(
                            'https://www.google.com/recaptcha/api/siteverify',
                            [
                                'secret' => $secret,
                                'response' => $value,
                                'remoteip' => $request->ip(),
                            ]
                        );

                        $body = $response->json();

                        if (!($body['success'] ?? false)) {
                            $fail(__('Verifikasi reCAPTCHA gagal. Silakan coba lagi.'));
                        }
                    } catch (Throwable $e) {
                        Log::error('Gagal memverifikasi reCAPTCHA.', [
                            'message' => $e->getMessage(),
                        ]);

                        $fail(__('Terjadi kesalahan saat memverifikasi reCAPTCHA. Silakan coba lagi.'));
                    }
                },
            ],
        ]);

        // ✅ Buat user baru
        User::create([
            'name' => $profile['name'],
            'username' => $profile['username'],
            'email' => $profile['email'],
            'password' => Hash::make($passwordData['password']),
            'role' => 'visitor',
            'phone' => $profile['phone'] ?? null,
            'gender' => $profile['gender'] ?? null,
        ]);

        $request->session()->forget('register.profile');

        return redirect()
            ->route('login')
            ->with('register_success', true)
            ->with('status', __('Akun berhasil dibuat. Silakan login untuk mulai belajar.'))
            ->withInput(['username' => $profile['username']]);
    }

    public function login(Request $request): RedirectResponse
    {
        $this->ensureUsernameSupport();

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        try {
            if (! Auth::attempt($credentials, $request->boolean('remember'))) {
                return back()->withErrors([
                    'username' => __('auth.failed'),
                ])->onlyInput('username');
            }

            $user = Auth::user();

            if (
                $user &&
                $user->role === 'tutor' &&
                Schema::hasColumn('users', 'is_active') &&
                ! $user->is_active
            ) {
                Auth::logout();

                return back()->withErrors([
                    'username' => __('Akun tentor ini sedang dinonaktifkan oleh admin.'),
                ])->onlyInput('username');
            }
        } catch (QueryException|PDOException $exception) {
            if ($this->isDatabaseConnectionIssue($exception)) {
                return back()->withErrors([
                    'username' => __('Koneksi ke database gagal. Pastikan layanan MySQL/XAMPP sudah berjalan dan pengaturan DB_HOST, DB_PORT, DB_USERNAME di file .env sesuai.'),
                ])->onlyInput('username');
            }

            throw $exception;
        }

        $request->session()->regenerate();

        return redirect()->intended($this->homeRouteFor(Auth::user()));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function homeRouteFor(?User $user): string
    {
        if (! $user) {
            return route('login');
        }

        return match ($user->role) {
            'tutor' => route('tutor.dashboard'),
            'student' => route('student.dashboard'),
            'admin' => route('admin.dashboard'),
            'visitor' => route('packages.index'),
            default => route('packages.index'),
        };
    }

    private function generateCaptchaChallenge(Request $request): array
    {
        $first = random_int(2, 9);
        $second = random_int(1, 8);
        $operators = ['+', '−'];
        $operator = $operators[array_rand($operators)];

        if ($operator === '−' && $second > $first) {
            [$first, $second] = [$second, $first];
        }

        $answer = $operator === '+'
            ? $first + $second
            : $first - $second;

        $request->session()->put('register.captcha_answer', (string) $answer);

        return [
            'question' => sprintf('%d %s %d = ?', $first, $operator, $second),
            'hint' => __('Masukkan jawaban dalam bentuk angka.'),
        ];
    }

    private function isDatabaseConnectionIssue(Throwable $exception): bool
    {
        $message = strtolower($exception->getMessage());

        if (str_contains($message, 'connection refused') || str_contains($message, 'actively refused')) {
            return true;
        }

        if ($exception instanceof QueryException && $exception->getPrevious()) {
            return $this->isDatabaseConnectionIssue($exception->getPrevious());
        }

        if ($exception instanceof PDOException && (int) $exception->getCode() === 2002) {
            return true;
        }

        return false;
    }

    private function ensureUsernameSupport(): void
    {
        try {
            if (! Schema::hasTable('users')) {
                return;
            }

            if (Schema::hasColumn('users', 'username')) {
                return;
            }

            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 50)->nullable()->unique()->after('name');
            });

            User::query()
                ->whereNull('username')
                ->orderBy('id')
                ->chunkById(100, function ($users) {
                    foreach ($users as $user) {
                        $user->forceFill([
                            'username' => $this->generateFallbackUsername($user),
                        ])->save();
                    }
                });
        } catch (Throwable $exception) {
            Log::error('Unable to ensure username support for authentication.', [
                'message' => $exception->getMessage(),
            ]);

            abort(500, __('Kolom username belum tersedia di database. Jalankan migrasi database MayClass kemudian coba lagi.'));
        }
    }

    private function generateFallbackUsername(User $user): string
    {
        $base = Str::slug($user->name) ?: 'user';
        $base = substr($base, 0, 40);

        if ($base === '') {
            $base = 'user';
        }

        $candidate = $base;

        if (! User::where('username', $candidate)->exists()) {
            return $candidate;
        }

        $candidate = rtrim(substr($base, 0, 32), '-') . '-' . $user->id;

        while (User::where('username', $candidate)->exists()) {
            $candidate = rtrim(substr($base, 0, 30), '-') . '-' . random_int(1000, 9999);
        }

        return $candidate;
    }
}
