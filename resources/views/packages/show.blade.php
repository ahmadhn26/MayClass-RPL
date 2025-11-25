<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $package['detail_title'] }} - MayClass</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />
        <style>
            :root {
                /* Palette Modern Teal */
                --primary: #0f766e;
                --primary-hover: #115e59;
                --primary-light: #ccfbf1;
                --accent: #f59e0b;
                
                --bg-body: #f8fafc;
                --surface: #ffffff;
                
                --text-main: #0f172a;
                --text-muted: #64748b;
                
                --border: #e2e8f0;
                --radius: 16px;
                
                --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
                --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-body);
                color: var(--text-main);
                line-height: 1.6;
                -webkit-font-smoothing: antialiased;
            }

            a { text-decoration: none; color: inherit; transition: all 0.2s; }
            ul { list-style: none; padding: 0; margin: 0; }
            img { display: block; max-width: 100%; }

            /* --- Layout --- */
            .container {
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 24px;
            }

            /* --- Navbar --- */
            header {
                background: var(--surface);
                border-bottom: 1px solid var(--border);
                position: sticky;
                top: 0;
                z-index: 100;
            }

            nav {
                height: 72px;
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

            /* Buttons */
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 10px 20px;
                border-radius: 8px;
                font-weight: 600;
                font-size: 0.9rem;
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
            .btn-primary:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: var(--shadow-md); }

            .btn-disabled {
                background: #f1f5f9;
                color: var(--text-muted);
                cursor: not-allowed;
                border-color: var(--border);
            }

            /* --- Main Content --- */
            .main-wrapper {
                padding: 40px 0 80px;
                display: grid;
                grid-template-columns: 1fr 380px; /* Sidebar fixed width */
                gap: 48px;
                align-items: start;
            }

            /* Left Column */
            .content-area {
                display: flex;
                flex-direction: column;
            }

            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: var(--text-muted);
                font-size: 0.9rem;
                font-weight: 500;
                margin-bottom: 24px;
            }
            .back-link:hover { color: var(--primary); }

            .package-tag {
                display: inline-block;
                background: var(--primary-light);
                color: var(--primary);
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                padding: 6px 12px;
                border-radius: 99px;
                margin-bottom: 16px;
                letter-spacing: 0.05em;
            }

            .package-title {
                font-size: 2.5rem;
                font-weight: 800;
                color: var(--text-main);
                margin: 0 0 12px;
                line-height: 1.2;
            }

            .package-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 16px;
                color: var(--text-muted);
                font-size: 1rem;
                margin-bottom: 32px;
                padding-bottom: 32px;
                border-bottom: 1px solid var(--border);
            }
            .package-meta span { display: flex; align-items: center; gap: 6px; }

            .description {
                font-size: 1.05rem;
                color: var(--text-main);
                margin-bottom: 40px;
            }

            .features-section h3 {
                font-size: 1.25rem;
                font-weight: 700;
                margin: 0 0 20px;
            }

            .features-list {
                display: grid;
                gap: 16px;
            }

            .feature-item {
                display: flex;
                gap: 12px;
                align-items: flex-start;
            }

            .check-icon {
                color: var(--primary);
                background: var(--primary-light);
                width: 24px; height: 24px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 0.8rem;
                font-weight: bold;
            }

            /* Right Column (Sticky Card) */
            .purchase-card {
                background: var(--surface);
                border: 1px solid var(--border);
                border-radius: var(--radius);
                box-shadow: var(--shadow-lg);
                padding: 24px;
                position: sticky;
                top: 100px; /* Stick below header */
            }

            .card-image {
                width: 100%;
                height: 200px;
                object-fit: cover;
                border-radius: 12px;
                margin-bottom: 24px;
                border: 1px solid var(--border);
            }

            .price-label {
                font-size: 0.9rem;
                color: var(--text-muted);
                margin-bottom: 4px;
            }

            .price-value {
                font-size: 2rem;
                font-weight: 800;
                color: var(--text-main);
                line-height: 1;
                margin-bottom: 24px;
            }

            .active-alert {
                background: #eff6ff;
                border: 1px solid #bfdbfe;
                color: #1e40af;
                padding: 12px;
                border-radius: 8px;
                font-size: 0.9rem;
                margin-top: 16px;
                line-height: 1.5;
            }

            /* Responsive */
            @media (max-width: 900px) {
                .main-wrapper {
                    grid-template-columns: 1fr;
                    gap: 32px;
                }
                .purchase-card {
                    position: static;
                    order: -1; /* Move to top on mobile */
                }
                .card-image { height: 180px; }
            }
        </style>
    </head>
    <body>
        @php($profileLink = $profileLink ?? null)
        @php($profileAvatar = $profileAvatar ?? asset('images/avatar-placeholder.svg'))
        @php($studentHasActivePackage = $studentHasActivePackage ?? false)
        @php($studentActivePackageName = $studentActivePackageName ?? null)
        @php($isStudent = auth()->check() && auth()->user()->role === 'student')

        <header>
            <nav class="container">
                <a href="/" class="brand">
                    <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" />
                </a>
                <div class="nav-actions">
                    @auth
                        <a href="{{ $profileLink ?? route('student.profile') }}" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 1px solid var(--border);">
                            <img src="{{ $profileAvatar }}" alt="Profil" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                        <form method="post" action="{{ route('logout') }}" style="margin:0">
                            @csrf
                            <button type="submit" class="btn btn-outline" style="border: none; color: #ef4444; font-weight: 600;">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    @endauth
                </div>
            </nav>
        </header>

        <div class="container main-wrapper">

            <div class="content-area">
                @if (session('package_full'))
                    <div style="background: #fef2f2; border: 1px solid #fecdd3; color: #b91c1c; padding: 14px 16px; border-radius: 12px; margin-bottom: 20px;">
                        {{ session('package_full') }}
                    </div>
                @endif

                @php($quotaLimit = $package['quota_limit'] ?? null)
                @php($quotaRemaining = $package['quota_remaining'] ?? null)
                @php($isFull = (bool) ($package['is_full'] ?? false))
                @php($quotaLabel = $quotaLimit === null ? 'Kuota tersedia' : 'Tersisa ' . max(0, (int) $quotaRemaining) . ' dari ' . (int) $quotaLimit . ' kursi')

                <a href="{{ route('packages.index') }}" class="back-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Kembali ke Katalog
                </a>

                <span class="package-tag">
                    {{ $package['tag'] ?? ($package['stage_label'] ?? 'Program') }}
                </span>
                
                <h1 class="package-title">{{ $package['detail_title'] }}</h1>
                
                <div class="package-meta">
                    <span>
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        {{ $package['stage_label'] ?? $package['stage'] ?? 'Program' }}
                    </span>
                    @if (! empty($package['grade_range']))
                        <span>
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            {{ $package['grade_range'] }}
                        </span>
                    @endif
                </div>

                <div class="description">
                    <p>{{ $package['summary'] }}</p>
                </div>

                <div class="features-section">
                    <h3>Fasilitas yang didapatkan:</h3>
                    <ul class="features-list" id="included">
                        @foreach ($package['included'] as $item)
                            <li class="feature-item">
                                <span class="check-icon">✓</span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if($subjects && $subjects->count() > 0)
                <div class="features-section">
                    <h3>Mata Pelajaran yang Dipelajari:</h3>
                    <ul class="features-list">
                        @foreach ($subjects as $subject)
                            <li class="feature-item">
                                <span class="check-icon">📚</span>
                                <span>{{ $subject->name }} <small style="color: var(--text-muted); font-weight: 500;">({{ $subject->level }})</small></span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <aside class="purchase-card">
                <img src="{{ $package['image'] }}" alt="{{ $package['detail_title'] }}" class="card-image" />

                <div class="price-label">Harga Paket</div>
                <div class="price-value">{{ $package['detail_price'] }}</div>

                <div style="margin: 10px 0 16px; color: {{ $isFull ? '#b91c1c' : 'var(--text-muted)' }}; font-weight: 600; font-size: 0.95rem;">
                    {{ $isFull ? 'Kuota Penuh' : $quotaLabel }}
                </div>

                @auth
                    @php($purchaseLocked = $isStudent && $studentHasActivePackage)

                    @if ($isFull)
                        <button class="btn btn-disabled" style="width: 100%;" disabled>
                            Kuota Penuh
                        </button>
                        <div class="active-alert" style="background: #fef2f2; color: #b91c1c; border-color: #fecdd3;">
                            Slot paket ini sedang penuh. Silakan pilih paket lain atau hubungi admin untuk informasi selanjutnya.
                        </div>
                    @elseif ($purchaseLocked)
                        <button class="btn btn-disabled" style="width: 100%;" disabled>
                            Paket Aktif Berlangsung
                        </button>
                        <div class="active-alert">
                            Kamu sedang aktif di paket <strong>{{ $studentActivePackageName ?? 'MayClass' }}</strong>.
                        </div>
                    @else
                        <a href="{{ route('checkout.show', $package['slug']) }}" class="btn btn-primary" style="width: 100%;">
                            Checkout
                        </a>
                    @endif
                @else
                    @if ($isFull)
                        <button class="btn btn-disabled" style="width: 100%;" disabled>
                            Kuota Penuh
                        </button>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary" style="width: 100%;">
                            Daftar & Checkout
                        </a>
                    @endif
                    <div style="text-align: center; margin-top: 12px; font-size: 0.9rem; color: var(--text-muted);">
                        Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">Masuk</a>
                    </div>
                @endauth
            </aside>

        </div>
    </body>
</html>
