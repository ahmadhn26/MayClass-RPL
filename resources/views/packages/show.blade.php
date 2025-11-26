<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $package['detail_title'] }} - MayClass</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            --primary: #0f766e;
            --primary-hover: #115e59;
            --primary-light: #ccfbf1;
            --bg-body: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 12px;
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 0 24px;
        }

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

        .brand img {
            height: 110px; /* diperbesar dari 40px */
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

        .btn-outline:hover {
            background: var(--bg-body);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-disabled {
            background: #f1f5f9;
            color: var(--text-muted);
            cursor: not-allowed;
            border-color: var(--border);
        }

        .main-wrapper {
            padding: 48px 0 80px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            width: fit-content;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .feature-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            padding: 32px;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 32px;
            align-items: center;
        }

        .card-left {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .package-tag {
            display: inline-block;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 99px;
            letter-spacing: 0.05em;
            width: fit-content;
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.2;
            margin: 0;
        }

        .card-subtitle {
            font-size: 1rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }

        .meta-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding-top: 12px;
            border-top: 1px solid var(--border);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .meta-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .card-right {
            display: flex;
            flex-direction: column;
            gap: 16px;
            text-align: center;
        }

        .price-box {
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-light) 0%, #ecf0f1 100%);
            border-radius: 10px;
        }

        .price-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .price-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }

        .quota-badge {
            padding: 10px 14px;
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #166534;
            font-weight: 600;
            text-align: center;
        }

        .quota-badge.full {
            background: #fef2f2;
            border-color: #fecdd3;
            color: #b91c1c;
        }

        .quota-badge.limited {
            background: #fef3c7;
            border-color: #fde68a;
            color: #92400e;
        }

        .btn-checkout {
            width: 100%;
            padding: 12px;
            font-weight: 600;
        }

        .alert-box {
            padding: 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            line-height: 1.4;
            text-align: center;
        }

        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }

        .alert-warning {
            background: #fef2f2;
            border: 1px solid #fecdd3;
            color: #b91c1c;
        }

        .features-wrapper {
            margin-top: 32px;
        }

        .features-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-main);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }

        .feature-item {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid var(--primary);
            font-size: 0.9rem;
        }

        .feature-icon {
            color: var(--primary);
            font-weight: bold;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .feature-text {
            color: var(--text-main);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .feature-card {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 24px;
            }

            .card-title {
                font-size: 1.5rem;
            }

            .meta-row {
                flex-direction: column;
                gap: 12px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav class="container">
            <a href="/" class="brand">
                <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" />
            </a>
            <div class="nav-actions">
                @auth
                    <a href="{{ route('student.profile') }}"
                        style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 1px solid var(--border);">
                        <img src="{{ auth()->user()->profile_picture ?? asset('images/avatar-placeholder.svg') }}"
                            alt="Profil" style="width: 100%; height: 100%; object-fit: cover;">
                    </a>
                    <form method="post" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button type="submit" class="btn btn-outline"
                            style="border: none; color: #ef4444; font-weight: 600;">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </nav>
    </header>

    <div class="container main-wrapper">
        @if (session('package_full'))
            <div class="alert-box alert-warning">
                {{ session('package_full') }}
            </div>
        @endif

        <a href="{{ route('packages.index') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Kembali ke Katalog
        </a>

        <div class="feature-card">
            <div class="card-left">
                <span class="package-tag">{{ $package['tag'] ?? 'Program' }}</span>
                <h1 class="card-title">{{ $package['detail_title'] }}</h1>
                <p class="card-subtitle">{{ $package['summary'] }}</p>
                <div class="meta-row">
                    <div class="meta-item">
                        <div class="meta-icon">📚</div>
                        <span>{{ $package['level'] }}</span>
                    </div>
                    <div class="meta-item">
                        <div class="meta-icon">📖</div>
                        <span>Kelas {{ $package['grade_range'] }}</span>
                    </div>
                    <div class="meta-item">
                        <div class="meta-icon">👥</div>
                        <span>Kuota {{ $package['max_students'] }} siswa</span>
                    </div>
                </div>
            </div>

            <div class="card-right">
                <div class="price-box">
                    <div class="price-label">Harga</div>
                    <div class="price-value">Rp{{ number_format($package['price'], 0, ',', '.') }}/bln</div>
                </div>

                @php
                    $quotaRemaining = $package['quota_remaining'] ?? 0;
                    $isFull = $package['is_full'] ?? false;
                    $isLimited = !$isFull && $quotaRemaining > 0 && $quotaRemaining <= 3;
                @endphp

                <div class="quota-badge {{ $isFull ? 'full' : ($isLimited ? 'limited' : '') }}">
                    @if ($isFull)
                        ❌ Kuota Penuh
                    @elseif ($isLimited)
                        ⚠ Sisa {{ $quotaRemaining }} kursi
                    @else
                        ✓ Slot Tersedia ({{ $quotaRemaining }}/{{ $package['max_students'] }})
                    @endif
                </div>

                @auth
                    @php
                        $isStudent = auth()->user()->role === 'student';
                        $hasActivePackage = $isStudent && auth()->user()->enrollments()->where('package_id', $package['id'])->where('is_active', true)->exists();
                    @endphp

                    @if ($isFull)
                        <button class="btn btn-disabled" disabled>Kuota Penuh</button>
                    @elseif ($hasActivePackage)
                        <button class="btn btn-disabled" disabled>Paket Aktif</button>
                        <div class="alert-box alert-info">Kamu sedang mengikuti paket ini</div>
                    @else
                        <a href="{{ route('checkout.show', $package['slug']) }}"
                            class="btn btn-primary btn-checkout">Checkout
                            Sekarang</a>
                    @endif
                @else
                    @if ($isFull)
                        <button class="btn btn-disabled" disabled>Kuota Penuh</button>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-checkout">Daftar & Checkout</a>
                    @endif
                @endauth
            </div>
        </div>

        @if (!empty($package['included']))
            <div class="features-wrapper">
                <div class="features-title">Program & Fasilitas</div>
                <div class="features-grid">
                    @foreach ($package['included'] as $feature)
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span class="feature-text">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($subjects->isNotEmpty())
            <div class="features-wrapper">
                <div class="features-title">Mata Pelajaran</div>
                <div class="features-grid">
                    @foreach ($subjects as $subject)
                        <div class="feature-item">
                            <span class="feature-icon">📝</span>
                            <span class="feature-text">{{ $subject->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</body>

</html>
