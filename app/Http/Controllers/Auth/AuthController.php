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
            'parent_phone' => ['required', 'string', 'max:30', 'regex:/^08[0-9]{8,13}$/'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
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

        if (!$profile) {
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

        if (!$profile) {
            return redirect()->route('register')->with('status', __('Silakan lengkapi data diri terlebih dahulu.'));
        }

        // âœ… Validasi data profile yang ada di session (tanpa recaptcha)
        $profileValidator = Validator::make($profile, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:4', 'max:50', Rule::unique(User::class, 'username')],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['nullable', 'string', 'max:30'],
            'parent_phone' => ['required', 'string', 'max:30', 'regex:/^08[0-9]{8,13}$/'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
        ]);

        if ($profileValidator->fails()) {
            return redirect()
                ->route('register')
                ->withErrors($profileValidator)
                ->withInput($profile);
        }

        // âœ… Validasi password + reCAPTCHA dari form password step
        $passwordData = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => [
                function ($attribute, $value, $fail) use ($request) {
                    $secret = config('services.recaptcha.secret');

                    // ðŸ”§ BYPASS: Skip validation di development jika reCAPTCHA tidak dikonfigurasi
                    if (!$secret) {
                        if (app()->environment('local', 'development')) {
                            Log::info('reCAPTCHA validation skipped (development mode, no credentials configured).');
                            return; // Skip validation
                        }

                        Log::warning('Google reCAPTCHA secret key tidak dikonfigurasi.');
                        $fail(__('Konfigurasi reCAPTCHA belum benar. Hubungi admin.'));

                        return;
                    }

                    // Jika kredensial ada, validasi harus required
                    if (empty($value)) {
                        $fail(__('Silakan selesaikan verifikasi reCAPTCHA.'));
                        return;
                    }

                    try {
                        $response = Http::withoutVerifying()->asForm()->post(
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

        // âœ… Buat user baru
        User::create([
            'name' => $profile['name'],
            'username' => $profile['username'],
            'email' => $profile['email'],
            'password' => Hash::make($passwordData['password']),
            'role' => 'visitor',
            'phone' => $profile['phone'] ?? null,
            'parent_phone' => $profile['parent_phone'] ?? null,
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
            // 1. Cek apakah user ada di database
            $userExists = User::where('username', $credentials['username'])->exists();

            if (!$userExists) {
                // Notifikasi: Pengguna belum memiliki akun, arahkan ke registrasi
                return redirect()
                    ->route('register')
                    ->withInput($request->only('username'))
                    ->with('error', __('Anda belum memiliki akun MayClass. Silakan daftar terlebih dahulu.'));
            }

            // 2. Coba otentikasi
            if (!Auth::attempt($credentials, $request->boolean('remember'))) {
                // Notifikasi: Username atau Password salah (jika user ada, tapi password salah)
                return back()
                    ->withInput($request->only('username'))
                    ->with('error', __('Username atau Password salah.'));
            }

            $user = Auth::user();

            if (
                $user &&
                $user->role === 'tutor' &&
                Schema::hasColumn('users', 'is_active') &&
                !$user->is_active
            ) {
                Auth::logout();

                return back()
                    ->withInput($request->only('username'))
                    ->with('error', __('Akun tentor ini sedang dinonaktifkan oleh admin.'));
            }
        } catch (QueryException | PDOException $exception) {
            if ($this->isDatabaseConnectionIssue($exception)) {
                return back()
                    ->withInput($request->only('username'))
                    ->with('error', __('Koneksi ke database gagal. Pastikan layanan MySQL/XAMPP sudah berjalan dan pengaturan DB_HOST, DB_PORT, DB_USERNAME di file .env sesuai.'));
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
        if (!$user) {
            return route('login');
        }

        // 1. Priority: Pending Payment Verification (Redirect to "Lihat Status")
        // "jika siswa login dan sedang menunggu notifikasi pembayaran"
        if (in_array($user->role, ['student', 'visitor'])) {
            // 1. Priority: Pending Payment Verification (Redirect to "Lihat Status")
            $pendingVerification = $user->orders()
                ->where('status', 'awaiting_verification')
                ->whereHas('package')
                ->with('package')
                ->latest()
                ->first();

            if ($pendingVerification && $pendingVerification->package) {
                return route('checkout.success', [
                    'slug' => $pendingVerification->package->slug,
                    'order' => $pendingVerification->id
                ]);
            }

            // 2. Priority: Active Package -> Student Dashboard (Bypass Profile)
            // "kalo dia sudah mempunyai paket yang aktif, maka pas setelah login dia langsung terhubung ke dashboard siswanya"
            $hasActivePackage = $user->enrollments()
                ->where('is_active', true)
                ->where(function ($query) {
                     $query->whereNull('ends_at')
                           ->orWhere('ends_at', '>', now());
                })
                ->exists();

            if ($hasActivePackage) {
                return route('student.dashboard');
            }

            // 3. Priority: First Time Login / Incomplete Profile
            // "siswa yang pertama kali login ... maka halaman yang muncul pertama kali adalah halaman profil"
            if (empty($user->address)) {
                return route('student.profile');
            }
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
        $operators = ['+', 'âˆ’'];
        $operator = $operators[array_rand($operators)];

        if ($operator === 'âˆ’' && $second > $first) {
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
            if (!Schema::hasTable('users')) {
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

        if (!User::where('username', $candidate)->exists()) {
            return $candidate;
        }

        $candidate = rtrim(substr($base, 0, 32), '-') . '-' . $user->id;

        while (User::where('username', $candidate)->exists()) {
            $candidate = rtrim(substr($base, 0, 30), '-') . '-' . random_int(1000, 9999);
        }

        return $candidate;
    }
}