<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>MayClass - Pilihan Paket Belajar</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        />
        <style>
            :root {
                /* Palette */
                --primary: #0f766e;
                --primary-hover: #115e59;
                --primary-light: #ccfbf1;
                --accent: #f59e0b; /* Orange untuk badge/best value */
                
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

            * { box-sizing: border-box; }

            body {
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-body);
                color: var(--text-main);
                line-height: 1.5;
                -webkit-font-smoothing: antialiased;
            }

            a { text-decoration: none; color: inherit; transition: all 0.2s; }
            ul { list-style: none; padding: 0; margin: 0; }

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
            .brand img { height: 110px; width: auto; }

            .nav-actions { display: flex; gap: 12px; align-items: center; }

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
            .btn-outline:hover { background: var(--bg-body); border-color: #cbd5e1; }

            .btn-primary {
                background: var(--primary);
                color: white;
            }
            .btn-primary:hover { background: var(--primary-hover); }

            .profile-avatar {
                width: 40px; height: 40px;
                border-radius: 50%;
                overflow: hidden;
                border: 2px solid var(--border);
            }
            .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }

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
            .alert-icon { flex-shrink: 0; margin-top: 2px; }

            /* --- Packages Section --- */
            .stage-section { margin-bottom: 60px; }
            
            .stage-header {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 24px;
            }
            .stage-header::after {
                content: ''; flex: 1; height: 1px; background: var(--border);
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
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 32px;
            }

            /* --- Card --- */
            .card {
                background: var(--surface);
                border: 1px solid var(--border);
                border-radius: var(--radius);
                padding: 32px 24px;
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
                top: 16px; right: 16px;
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

            .card-header { margin-bottom: 20px; }
            
            .pkg-title {
                font-size: 1.25rem;
                font-weight: 700;
                margin: 0 0 4px;
                padding-right: 60px; /* Space for badge */
            }
            
            .pkg-level {
                font-size: 0.9rem;
                color: var(--text-muted);
                font-weight: 500;
            }

            .pkg-price-wrapper {
                margin: 24px 0;
                padding-bottom: 24px;
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

            .btn-block { width: 100%; }
            .btn-ghost {
                background: var(--bg-body);
                color: var(--text-main);
                border: 1px solid transparent;
            }
            .btn-ghost:hover { background: #e2e8f0; }

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
                .page-hero h1 { font-size: 2rem; }
                .grid { grid-template-columns: 1fr; }
            }
        </style>
    </head>
    <body>
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
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <strong>Paket Aktif Berlangsung</strong>
                        <p style="margin: 4px 0 0; font-size: 0.95rem;">
                            Kamu saat ini terdaftar di paket <strong>{{ $studentActivePackageName ?? 'Belajar' }}</strong>. Nikmati akses penuh hingga masa berlaku habis.
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
                                @php($features = collect($package['card_features'] ?? $package['features'] ?? [])->take(5))
                                @php($quotaLimit = $package['quota_limit'] ?? null)
                                @php($quotaRemaining = $package['quota_remaining'] ?? null)
                                @php($isFull = (bool) ($package['is_full'] ?? false))
                                @php($quotaLabel = $quotaLimit === null ? 'Kuota tersedia' : 'Tersisa ' . max(0, (int) $quotaRemaining) . ' dari ' . (int) $quotaLimit . ' kursi')
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
                                        @if (!empty($package['detail_price']))
                                            <div class="pkg-period">{{ $package['detail_price'] }}</div>
                                        @endif
                                        <div style="margin-top: 10px; font-size: 0.9rem; color: {{ $isFull ? '#b91c1c' : 'var(--text-muted)' }}; font-weight: 600;">
                                            {{ $isFull ? 'Kuota Penuh' : $quotaLabel }}
                                        </div>
                                        @if ($package['summary'] ?? false)
                                            <div style="margin-top: 12px; font-size: 0.9rem; color: var(--text-muted);">
                                                {{ $package['summary'] }}
                                            </div>
                                        @endif
                                    </div>

                                    <ul class="pkg-features">
                                        @foreach ($features as $feature)
                                            <li class="feature-item">
                                                <svg class="check-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="card-actions">
                                        @auth
                                            @if ($isFull)
                                                <button class="btn btn-block btn-disabled" disabled>Kuota Penuh</button>
                                            @elseif (auth()->user()->role === 'student' && $studentHasActivePackage)
                                                <button class="btn btn-block btn-disabled" disabled>Sedang Aktif</button>
                                            @else
                                                <a href="{{ route('packages.show', $package['slug']) }}" class="btn btn-block btn-primary">
                                                    Pilih Paket
                                                </a>
                                            @endif
                                        @else
                                            @if ($isFull)
                                                <button class="btn btn-block btn-disabled" disabled>Kuota Penuh</button>
                                            @else
                                                <a href="{{ route('register') }}" class="btn btn-block btn-primary">
                                                    Daftar &amp; Pilih
                                                </a>
                                            @endif
                                        @endauth
                                        
                                        @if(auth()->check() && !(auth()->user()->role === 'student' && $studentHasActivePackage))

                                        @endif
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
    </body>
</html>
