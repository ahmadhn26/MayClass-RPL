<?php

use App\Http\Controllers\Admin\AccountController as AdminAccountController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FinanceController as AdminFinanceController;
use App\Http\Controllers\Admin\LandingContentController as AdminLandingContentController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PaymentMethodController as AdminPaymentMethodController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\ScheduleSessionController as AdminScheduleSessionController;
use App\Http\Controllers\Admin\ScheduleTemplateController as AdminScheduleTemplateController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\TentorController as AdminTentorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\MaterialController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Student\ScheduleController;
use App\Http\Controllers\Tutor\AccountController as TutorAccountController;
use App\Http\Controllers\Tutor\DashboardController as TutorDashboardController;
use App\Http\Controllers\Tutor\MaterialController as TutorMaterialController;
use App\Http\Controllers\Tutor\QuizController as TutorQuizController;
use App\Http\Controllers\Tutor\ScheduleController as TutorScheduleController;
use App\Http\Controllers\Tutor\ScheduleSessionController;
use App\Http\Controllers\Tutor\ScheduleTemplateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Models\Package;
use App\Support\PackagePresenter;
use App\Support\ProfileAvatar;
use App\Support\ProfileLinkResolver;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    $catalog = collect();
    $stageDefinitions = config('mayclass.package_stages', []);

    if (Schema::hasTable('packages')) {
        $query = Package::query()->withQuotaUsage()->with('subjects')->orderBy('level')->orderBy('price');

        if (Schema::hasTable('package_features')) {
            $query->with(['cardFeatures' => fn($features) => $features->orderBy('position')]);
        }

        $packages = $query->get();
        $catalog = PackagePresenter::groupByStage($packages);
    }

    $user = Auth::user();

    // Fetch Landing Content
    $landingContents = \App\Models\LandingContent::where('is_active', true)
        ->orderBy('order')
        ->get()
        ->groupBy('section');

    // Fetch Documentation - 20 latest (up to 30 days old)
    $documentations = collect();
    if (Schema::hasTable('documentations')) {
        $documentations = \App\Models\Documentation::where('is_active', true)
            ->where('activity_date', '>=', now()->subDays(30))
            ->orderBy('activity_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    // Check User Status
    $hasActivePackage = false;
    $pendingOrder = null;

    if ($user) {
        $hasActivePackage = \App\Support\StudentAccess::hasActivePackage($user);

        // Cari order yang belum selesai (pending/waiting) ATAU yang sedang diverifikasi admin
        $pendingOrder = \App\Models\Order::where('user_id', $user->id)
            ->whereIn('status', [
                // Status untuk tombol "Lanjut Bayar"
                'initiated',
                'pending',
                'awaiting_payment',

                // Status untuk tombol "Lihat Status"
                'awaiting_verification'
            ])
            ->with('package')
            ->latest('id')
            ->first();
    }

    // Get statistics for hero section
    // Total students who have ever joined (all students regardless of status)
    $totalStudents = \App\Models\User::where('role', 'student')
        ->count();

    // Total active tutors only
    $totalActiveTutors = \App\Models\User::where('role', 'tutor')
        ->where('is_active', true)
        ->count();

    // Get all students with their avatars for carousel (students who have joined)
    $studentsJoined = \App\Models\User::where('role', 'student')
        ->select('id', 'name', 'avatar_path')
        ->limit(10)
        ->get()
        ->map(function ($student) {
            return [
                'name' => $student->name,
                'avatar' => ProfileAvatar::forUser($student)
            ];
        });

    // Get active tutors with their avatars for carousel
    $activeTutors = \App\Models\User::where('role', 'tutor')
        ->where('is_active', true)
        ->with('tutorProfile')
        ->limit(10)
        ->get()
        ->map(function ($tutor) {
            $profile = $tutor->tutorProfile;
            return [
                'name' => $tutor->name,
                'role' => $profile?->headline ?? 'Tentor Spesialis',
                'quote' => $profile?->bio ? \Illuminate\Support\Str::limit($profile->bio, 80) : 'Siap membimbing kamu meraih impian.',
                'avatar' => ProfileAvatar::forUser($tutor),
                'meta' => array_filter([
                    $profile?->education ?? 'Lulusan Terbaik',
                    ($profile && $profile->experience_years ? $profile->experience_years . ' Tahun Pengalaman' : null)
                ])
            ];
        });

    return view('welcome', [
        'landingPackages' => $catalog,
        'stageDefinitions' => $stageDefinitions,
        'profileLink' => ProfileLinkResolver::forUser($user),
        'profileAvatar' => ProfileAvatar::forUser($user),
        'landingContents' => $landingContents,
        'documentations' => $documentations,
        'hasActivePackage' => $hasActivePackage,
        'pendingOrder' => $pendingOrder,
        'totalStudents' => $totalStudents,
        'totalActiveTutors' => $totalActiveTutors,
        'studentsJoined' => $studentsJoined,
        'activeTutors' => $activeTutors,
    ]);
})->name('home');

Route::get('/gabung', [AuthController::class, 'join'])->name('join');

// Temporary debug route - REMOVE AFTER TESTING
Route::get('/debug-google', function () {
    return response()->json([
        'client_id' => config('services.google.client_id'),
        'client_secret' => config('services.google.client_secret') ? 'SET' : 'NOT SET',
        'redirect' => config('services.google.redirect'),
    ]);
});

// Temporary test route for email preview
Route::get('/test-mail', function () {
    $user = new \App\Models\User([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $package = new \App\Models\Package([
        'detail_title' => 'Paket Belajar Premium',
    ]);

    $order = new \App\Models\Order([
        'total' => 150000,
        'paid_at' => now(),
    ]);

    $order->setRelation('user', $user);
    $order->setRelation('package', $package);

    return new \App\Mail\PaymentApproved($order);
});

// FIX CONFLICT ROUTE - Run this once if images are 403/404!
Route::get('/fix-conflict', function () {
    $log = [];

    // 1. Clear Route Cache (Crucial for new routes to work)
    try {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $log[] = 'Route cache cleared.';
    } catch (\Exception $e) {
        $log[] = 'Failed to clear route cache: ' . $e->getMessage();
    }

    // 2. Remove "public/storage" if it exists (It blocks our bypass route)
    $linkPath = public_path('storage');
    if (file_exists($linkPath) || is_link($linkPath)) {
        try {
            // Rename instead of delete for safety
            $backupName = 'storage_backup_' . time(); // e.g., storage_backup_123456
            rename($linkPath, public_path($backupName));
            $log[] = "BLOCKING FOLDER REMOVED! Renamed 'public/storage' to 'public/{$backupName}'.";
        } catch (\Exception $e) {
            $log[] = "Failed to remove blocking folder: " . $e->getMessage() . " (Please delete 'public/storage' manually via File Manager)";
        }
    } else {
        $log[] = "Good news: No blocking 'public/storage' folder found.";
    }

    return implode('<br>', $log) . '<br><br><b>Now try refreshing your website!</b>';
});

// Symlink Bypass Route - Serves storage files directly via Laravel
Route::get('storage/{path}', function ($path) {
    // Check if file exists in storage/app/public
    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $filePath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
    return response()->file($filePath);
})->where('path', '.*');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{slug}', [PackageController::class, 'show'])->name('packages.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/lupa-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/register/details', [AuthController::class, 'storeRegisterDetails'])->name('register.details');
    Route::get('/register/password', [AuthController::class, 'showPasswordStep'])->name('register.password');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

    // Login Google
    Route::controller(GoogleController::class)->group(function () {
        Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
        Route::get('auth/google/callback', 'handleGoogleCallback');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/checkout/{slug}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{slug}', [CheckoutController::class, 'store'])->name('checkout.process');
    Route::get('/checkout/{slug}/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/{slug}/orders/{order}/expire', [CheckoutController::class, 'expire'])->name('checkout.expire');
    Route::post('/checkout/{order}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/{slug}/status/{order}', [CheckoutController::class, 'status'])->name('checkout.status');

    Route::get('/profile', [ProfileController::class, 'show'])->name('student.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('student.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('student.profile.password');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('subscribed')->group(function () {
        Route::get('/jadwal', [ScheduleController::class, 'index'])->name('schedule');
        Route::get('/materi', [MaterialController::class, 'index'])->name('materials');
        Route::get('/materi/{slug}/open', [MaterialController::class, 'open'])->name('materials.open');
        Route::get('/materi/{slug}/download', [MaterialController::class, 'download'])->name('materials.download');
        Route::get('/materi/{slug}', [MaterialController::class, 'show'])->name('materials.show');
        Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');
        Route::get('/quiz/{slug}', [QuizController::class, 'show'])->name('quiz.show');
    });
});

Route::middleware(['auth', 'role:tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/materi', [TutorMaterialController::class, 'index'])->name('materials.index');
    Route::get('/materi/tambah', [TutorMaterialController::class, 'create'])->name('materials.create');
    Route::post('/materi', [TutorMaterialController::class, 'store'])->name('materials.store');
    Route::get('/materi/{material:slug}/preview', [TutorMaterialController::class, 'preview'])->name('materials.preview');
    Route::get('/materi/{material:slug}/download', [TutorMaterialController::class, 'download'])->name('materials.download');
    Route::get('/materi/{material:slug}/edit', [TutorMaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/materi/{material:slug}', [TutorMaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materi/{material}', [TutorMaterialController::class, 'destroy'])->name('materials.destroy');

    Route::get('/quiz', [TutorQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quiz/tambah', [TutorQuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quiz', [TutorQuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quiz/{quiz:slug}/edit', [TutorQuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quiz/{quiz:slug}', [TutorQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [TutorQuizController::class, 'destroy'])->name('quizzes.destroy');

    // API: Get subjects for a package
    Route::get('/packages/{package}/subjects', [TutorMaterialController::class, 'getPackageSubjects'])->name('packages.subjects');

    Route::get('/jadwal', [TutorScheduleController::class, 'index'])->name('schedule.index');

    Route::get('/pengaturan', [TutorAccountController::class, 'edit'])->name('account.edit');
    Route::put('/pengaturan', [TutorAccountController::class, 'update'])->name('account.update');
    Route::put('/pengaturan/password', [TutorAccountController::class, 'updatePassword'])->name('account.password');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/account', [AdminAccountController::class, 'edit'])->name('account.edit');
    Route::put('/account', [AdminAccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AdminAccountController::class, 'updatePassword'])->name('account.password');

    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [AdminStudentController::class, 'show'])->name('students.show');
    Route::post('/students/{student}/toggle-status', [AdminStudentController::class, 'toggleStatus'])->name('students.toggle-status');
    Route::post('/students/{student}/reset-password', [AdminStudentController::class, 'resetPassword'])->name('students.reset-password');
    Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');

    Route::resource('tentors', AdminTentorController::class)->except(['show', 'create']);

    Route::get('/packages', [AdminPackageController::class, 'index'])->name('packages.index');
    Route::post('/packages', [AdminPackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [AdminPackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}', [AdminPackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [AdminPackageController::class, 'destroy'])->name('packages.destroy');
    Route::get('/packages/{package}/subjects', [AdminPackageController::class, 'getSubjects'])->name('packages.subjects');

    Route::get('/subjects', [AdminSubjectController::class, 'index'])->name('subjects.index');
    Route::post('/subjects', [AdminSubjectController::class, 'store'])->name('subjects.store');
    Route::post('/subjects', [AdminSubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}/edit', [AdminSubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [AdminSubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [AdminSubjectController::class, 'destroy'])->name('subjects.destroy');

    Route::get('/finance', [AdminFinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance/{order}/approve', [AdminFinanceController::class, 'approve'])->name('finance.approve');
    Route::post('/finance/{order}/reject', [AdminFinanceController::class, 'reject'])->name('finance.reject');
    Route::post('/finance/{order}/refund', [AdminFinanceController::class, 'refund'])->name('finance.refund');
    Route::get('/finance/{order}/proof', [AdminFinanceController::class, 'downloadProof'])->name('finance.proof');

    Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');

    Route::post('/schedule/template', [AdminScheduleTemplateController::class, 'store'])->name('schedule.templates.store');
    Route::put('/schedule/template/{template}', [AdminScheduleTemplateController::class, 'update'])->name('schedule.templates.update');
    Route::delete('/schedule/template/{template}', [AdminScheduleTemplateController::class, 'destroy'])->name('schedule.templates.destroy');
    Route::put('/schedule/{session}', [AdminScheduleSessionController::class, 'update'])->name('schedule.sessions.update');
    Route::post('/schedule/{session}/cancel', [AdminScheduleSessionController::class, 'cancel'])->name('schedule.sessions.cancel');
    Route::post('/schedule/{session}/restore', [AdminScheduleSessionController::class, 'restore'])->name('schedule.sessions.restore');

    Route::resource('landing-content', \App\Http\Controllers\Admin\LandingContentController::class)->except(['show', 'create', 'edit']);

    Route::get('/documentations', [\App\Http\Controllers\Admin\DocumentationController::class, 'index'])->name('documentations.index');
    Route::post('/documentations', [\App\Http\Controllers\Admin\DocumentationController::class, 'store'])->name('documentations.store');
    Route::put('/documentations/{documentation}', [\App\Http\Controllers\Admin\DocumentationController::class, 'update'])->name('documentations.update');
    Route::delete('/documentations/{documentation}', [\App\Http\Controllers\Admin\DocumentationController::class, 'destroy'])->name('documentations.destroy');

    // Payment Methods
    Route::get('/payment-methods', [AdminPaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('/payment-methods', [AdminPaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::put('/payment-methods/{paymentMethod}', [AdminPaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{paymentMethod}', [AdminPaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
});