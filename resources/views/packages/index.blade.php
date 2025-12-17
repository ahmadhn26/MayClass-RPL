<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MayClass - Pilihan Paket Belajar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            /* Palette */
            --primary: #0f766e;
            --primary-hover: #115e59;
            --primary-light: #ccfbf1;
            --accent: #f59e0b;
            /* Orange untuk badge/best value */

            --bg-body: #f8fafc;
            --surface: #ffffff;

            --text-main: #0f172a;
            --text-muted: #64748b;

            --border: #e2e8f0;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);

            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* --- Layout --- */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* --- Navigation --- */
        header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        nav {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
        }

        .brand img {
            height: 56px;
            width: auto;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .btn-outline {
            background: transparent;
            border-color: var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: var(--bg-body);
            border-color: #cbd5e1;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--border);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- Hero Title --- */
        .page-hero {
            text-align: center;
            padding: 60px 0 40px;
        }

        .page-hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 0 0 12px;
            letter-spacing: -0.025em;
        }

        .page-hero p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        /* --- Active Package Alert --- */
        .alert-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 16px;
            border-radius: var(--radius);
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* --- Packages Section --- */
        .stage-section {
            margin-bottom: 60px;
        }

        .stage-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .stage-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .stage-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        /* --- Grid --- */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            align-items: start;
        }

        /* --- Card --- */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: #cbd5e1;
        }

        /* Highlighted Card Style (e.g. Best Value) */
        .card[data-highlight="true"] {
            border-color: var(--primary);
            box-shadow: 0 0 0 1px var(--primary), var(--shadow-md);
        }

        .badge-tag {
            position: absolute;
            top: 16px;
            right: 16px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 99px;
            letter-spacing: 0.05em;
        }

        .badge-best {
            background: #fef3c7;
            color: #b45309;
        }

        .card-header {
            margin-bottom: 20px;
        }

        .pkg-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0 0 4px;
            padding-right: 60px;
            /* Space for badge */
            line-height: 1.3;
        }

        .pkg-level {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .pkg-price-wrapper {
            margin: 16px 0;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .pkg-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
        }

        .pkg-period {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .pkg-features {
            flex: 1;
            margin-bottom: 24px;
        }

        .feature-item {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 0.95rem;
            color: var(--text-muted);
            align-items: flex-start;
        }

        .check-icon {
            color: var(--primary);
            flex-shrink: 0;
            margin-top: 3px;
        }

        .card-actions {
            margin-top: auto;
        }

        .btn-block {
            width: 100%;
        }

        .btn-ghost {
            background: var(--bg-body);
            color: var(--text-main);
            border: 1px solid transparent;
        }

        .btn-ghost:hover {
            background: #e2e8f0;
        }

        .btn-disabled {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
            border: 1px solid #e2e8f0;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px dashed var(--border);
        }

        /* Footer */
        .simple-footer {
            text-align: center;
            padding: 40px 0;
            margin-top: 40px;
            color: var(--text-muted);
            font-size: 0.9rem;
            border-top: 1px solid var(--border);
        }

        @media (max-width: 640px) {
            .page-hero h1 {
                font-size: 2rem;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <style>
        /* ... existing styles ... */
        [x-cloak] {
            display: none !important;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
            padding: 20px;
        }

        .modal-container {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 900px;
            /* Wider for 2 cols */
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @media (max-width: 768px) {
            .modal-container {
                max-width: 100%;
                border-radius: 16px 16px 0 0;
                height: auto;
                max-height: 85vh;
                position: absolute;
                bottom: 0;
                margin: 0;
            }
        }

        @keyframes modalPop {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s;
            z-index: 10;
        }

        .modal-close:hover {
            background: #f1f5f9;
            color: var(--danger);
        }
    </style>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body x-data="{ 
    modalOpen: false, 
    pkg: {}, 
    openModal(packageData) { 
        this.pkg = packageData; 
        this.modalOpen = true; 
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        this.modalOpen = false;
        document.body.style.overflow = 'auto';
    }
}">
    @php($profileLink = $profileLink ?? null)
    @php($profileAvatar = $profileAvatar ?? asset('images/avatar-placeholder.svg'))
    @php($studentHasActivePackage = $studentHasActivePackage ?? false)
    @php($studentActivePackageName = $studentActivePackageName ?? null)

    <header>
        <nav class="container">
            <a href="/" class="brand">
                <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo">
            </a>

            @auth
                <div class="nav-actions">
                    <a href="{{ $profileLink ?? route('student.profile') }}" class="profile-avatar">
                        <img src="{{ $profileAvatar }}" alt="Profil">
                    </a>
                    <form method="post" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button type="submit" class="btn btn-outline">Keluar</button>
                    </form>
                </div>
            @else
                <div class="nav-actions">
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                </div>
            @endauth
        </nav>
    </header>

    <div class="page-hero container">
        <h1>Pilih Paket Belajar</h1>
        <p>Investasikan masa depanmu dengan metode belajar terstruktur dan bimbingan mentor terbaik.</p>
    </div>

    <main class="container">
        @if (auth()->check() && auth()->user()->role === 'student' && $studentHasActivePackage)
            <div class="alert-box">
                <div class="alert-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <strong>Paket Aktif Berlangsung</strong>
                    <p style="margin: 4px 0 0; font-size: 0.95rem;">
                        Kamu saat ini terdaftar di paket <strong>{{ $studentActivePackageName ?? 'Belajar' }}</strong>.
                        Nikmati akses penuh hingga masa berlaku habis.
                    </p>
                </div>
            </div>
        @endif

        @php($catalog = collect($catalog ?? []))

        @if ($catalog->isNotEmpty())
        @foreach ($catalog as $group)
        <section class="stage-section">
            <div class="stage-header">
                <h2 class="stage-title">{{ $group['stage_label'] ?? $group['stage'] }}</h2>
            </div>

            <div class="grid">
                @foreach ($group['packages'] as $package)
                @php($features = collect($package['card_features'] ?? $package['features'] ?? [])->take(3))
                @php($quotaLimit = $package['quota_limit'] ?? null)
                @php($quotaRemaining = $package['quota_remaining'] ?? null)
                @php($isFull = (bool) ($package['is_full'] ?? false))
                @php($quotaLabel = $quotaLimit === null ? 'Kuota tersedia' : 'Sisa ' . max(0, (int) $quotaRemaining) . ' kursi')
                @php($isBestValue = ($package['tag'] ?? '') === 'Best Value' || ($package['tag'] ?? '') === 'Terlaris')

                            <article class="card" data-highlight="{{ $isBestValue ? 'true' : 'false' }}">
                                @if (!empty($package['tag']))
                                    <span class="badge-tag {{ $isBestValue ? 'badge-best' : '' }}">
                                        {{ $package['tag'] }}
                                    </span>
                                @endif

                                <div class="card-header">
                                    <h3 class="pkg-title">{{ $package['detail_title'] }}</h3>
                                    <div class="pkg-level">
                                        {{ $group['stage_label'] ?? $group['stage'] }}
                                        @if (!empty($package['grade_range']))
                                            &bull; {{ $package['grade_range'] }}
                                        @endif
                                    </div>
                                </div>

                                <div class="pkg-price-wrapper">
                                    <div class="pkg-price">{{ $package['card_price'] }}</div>
                                    <div class="pkg-period">per bulan</div>
                                </div>

                                <div
                                    style="margin-bottom: 16px; font-size: 0.85rem; font-weight: 600; color: {{ $isFull ? '#ef4444' : '#10b981' }};">
                                    {{ $isFull ? '• Kuota Penuh' : '• ' . $quotaLabel }}
                                </div>

                                <ul class="pkg-features">
                                    @foreach ($features as $feature)
                                        <li class="feature-item">
                                            <svg class="check-icon" width="16" height="16" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="card-actions">
                                    @auth
                                        @if ($isFull)
                                            <button class="btn btn-block btn-disabled" disabled>Penuh</button>
                                        @elseif (auth()->user()->role === 'student' && $studentHasActivePackage)
                                            <button class="btn btn-block btn-disabled" disabled>Aktif</button>
                                        @else
                                            <button type="button" @click="openModal({{ json_encode($package) }})"
                                                class="btn btn-block btn-primary">
                                                Detail Paket
                                            </button>
                                        @endif
                                    @else
                                        @if ($isFull)
                                            <button class="btn btn-block btn-disabled" disabled>Penuh</button>
                                        @else
                                            <button type="button" @click="openModal({{ json_encode($package) }})"
                                                class="btn btn-block btn-primary">
                                                Detail Paket
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </section>
                    @endforeach
                @else
        <div class="empty-state">
            <div style="font-size: 3rem; margin-bottom: 16px;">📦</div>
            <h2 style="margin-top:0;">Paket Belum Tersedia</h2>
            <p>Katalog paket belajar sedang disiapkan oleh admin. Silakan kembali lagi nanti.</p>
            @if(!auth()->check())
                <div style="margin-top: 24px;">
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk Dashboard</a>
                </div>
            @endif
        </div>
        @endif
    </main>

    <footer class="simple-footer container">
        &copy; {{ date('Y') }} MayClass Education. Semua hak dilindungi.
    </footer>

    <!-- Package Detail Modal -->
    <div class="modal-overlay" x-show="modalOpen" x-transition.opacity x-cloak @click.self="closeModal()">
        <div class="modal-container">
            <button @click="closeModal()" class="modal-close">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <div style="padding: 32px; max-width: 900px; width: 100%;">

                <div style="display: grid; grid-template-columns: 1fr 340px; gap: 40px; align-items: start;">
                    <!-- LEFT COLUMN -->
                    <div>
                        <!-- Badge Program -->
                        <span class="badge-tag"
                            style="position: static; display: inline-block; margin-bottom: 16px; background: #ccfbf1; color: #0f766e; font-size: 0.8rem; padding: 6px 12px; border-radius: 99px; font-weight: 700;"
                            x-show="pkg.tag" x-text="pkg.tag ?? 'PROGRAM'"></span>

                        <!-- Title -->
                        <h2 style="font-size: 2.2rem; margin: 0 0 12px; line-height: 1.2; font-weight: 800; color: var(--text-main);"
                            x-text="pkg.detail_title"></h2>

                        <!-- Description -->
                        <div style="margin-bottom: 24px; color: var(--text-muted); line-height: 1.6; font-size: 1rem;"
                            x-text="pkg.description"></div>

                        <!-- Badges Row -->
                        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 32px;">
                            <div
                                style="display: flex; align-items: center; gap: 8px; background: #f1f5f9; padding: 8px 12px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; color: var(--text-muted);">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span x-text="pkg.stage_label || pkg.stage"></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; background: #f0f9ff; color: #0369a1; padding: 8px 12px; border-radius: 8px; font-size: 0.9rem; font-weight: 600;"
                                x-show="pkg.grade_range">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                <span x-text="pkg.grade_range"></span>
                            </div>
                            <div
                                style="display: flex; align-items: center; gap: 8px; background: #f0fdf4; color: #15803d; padding: 8px 12px; border-radius: 8px; font-size: 0.9rem; font-weight: 600;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span x-text="'Kuota ' + (pkg.quota_limit || '∞') + ' siswa'"></span>
                            </div>
                        </div>

                        <!-- Subjects Section -->
                        <div style="border-top: 1px solid var(--border); padding-top: 24px;">
                            <h4 style="margin: 0 0 16px; font-size: 1.1rem; color: var(--text-main);">Mata Pelajaran
                            </h4>

                            <div style="display: grid; gap: 12px;">
                                <template x-for="subject in (pkg.subjects || [])">
                                    <div
                                        style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                        <div
                                            style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                            📚
                                        </div>
                                        <div style="font-weight: 600; color: var(--text-main);" x-text="subject.name">
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!pkg.subjects || pkg.subjects.length === 0"
                                    style="color: var(--text-muted); font-style: italic;">
                                    Tidak ada data mata pelajaran.
                                </div>
                            </div>
                        </div>

                        <!-- Features Section (Moved to Left) -->
                        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                            <h4 style="margin: 0 0 16px; font-size: 1.1rem; color: var(--text-main);">Fasilitas
                                Termasuk:</h4>
                            <ul
                                style="font-size: 0.95rem; color: var(--text-muted); padding: 0; list-style: none; display: grid; gap: 10px;">
                                <template x-for="feature in (pkg.features || [])">
                                    <li style="display: flex; gap: 10px; align-items: start;">
                                        <div style="color: var(--primary); margin-top: 2px;">
                                            <svg width="18" height="18" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <span x-text="feature" style="line-height: 1.5;"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN (Sticky Price Card) -->
                    <div
                        style="background: white; border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow-md);">
                        <div
                            style="background: #ecfdf5; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 16px;">
                            <div
                                style="font-size: 0.75rem; font-weight: 700; color: #047857; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                                Harga</div>
                            <div style="font-size: 1.75rem; font-weight: 800; color: #065f46; line-height: 1;"
                                x-text="pkg.card_price"></div>
                            <div style="font-size: 0.9rem; color: #047857; margin-top: 4px;">per bulan</div>
                        </div>

                        <div
                            style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 10px; border-radius: 8px; text-align: center; font-size: 0.9rem; font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span
                                x-text="'Slot Tersedia (' + (pkg.quota_remaining !== undefined ? Math.max(0, pkg.quota_remaining) : '-') + '/' + (pkg.quota_limit || '-') + ')'"></span>
                        </div>

                        <a :href="'/checkout/' + pkg.slug" class="btn btn-primary btn-block"
                            style="padding: 14px; font-size: 1rem; border-radius: 10px; width: 100%; display: flex; justify-content: center;">
                            Checkout Sekarang
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>