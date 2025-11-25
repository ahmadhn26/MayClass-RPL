<!DOCTYPE html>
<html lang="id" data-role="student" data-page="{{ $page ?? '' }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ trim(($title ?? 'MayClass') . ' - MayClass') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <style>
            :root {
                color-scheme: only light;
                --student-primary: #2b9083;
                --student-primary-soft: #35b6a8;
                --student-accent: #5f6af8;
                --student-surface: #ffffff;
                --student-surface-muted: #f5faf9;
                --student-border: rgba(36, 92, 92, 0.12);
                --student-text: #132b33;
                --student-text-muted: #5d6e75;
                --student-radius-lg: 28px;
                --student-radius-md: 20px;
                --student-radius-sm: 12px;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: "Poppins", sans-serif;
                background: linear-gradient(180deg, #e6f6f3 0%, #ffffff 55%);
                color: var(--student-text);
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .student-shell {
                display: flex;
                flex-direction: column;
                gap: 28px;
                padding: 28px clamp(20px, 4vw, 56px) 48px;
            }

            .student-navbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 24px;
                padding: 20px clamp(20px, 3vw, 40px);
                background: rgba(255, 255, 255, 0.82);
                border-radius: var(--student-radius-lg);
                box-shadow: 0 36px 80px rgba(27, 119, 110, 0.14);
                backdrop-filter: blur(16px);
            }

            .student-navbar__brand {
                display: inline-flex;
                align-items: center;
                gap: 14px;
                font-weight: 600;
                color: var(--student-primary);
            }

            .student-navbar__brand img {
                width: 90px;
                height: 70px;
                border-radius: 12px;
                object-fit: cover;
            }

            .student-navbar__links {
                display: flex;
                align-items: center;
                gap: clamp(16px, 3vw, 40px);
                font-size: 0.95rem;
                color: var(--student-text-muted);
            }

            .student-navbar__link {
                position: relative;
                padding-bottom: 6px;
                font-weight: 500;
                transition: color 0.2s ease;
            }

            .student-navbar__link.is-active {
                color: var(--student-primary);
            }

            .student-navbar__link.is-active::after {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                bottom: -10px;
                height: 4px;
                border-radius: 999px;
                background: linear-gradient(120deg, var(--student-primary-soft), var(--student-accent));
            }

            .student-navbar__actions {
                display: inline-flex;
                align-items: center;
                gap: 16px;
            }

            .student-navbar__profile {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                padding: 10px 18px;
                border-radius: 999px;
                background: rgba(47, 152, 140, 0.12);
                color: var(--student-primary);
                font-size: 0.92rem;
            }

            .student-navbar__avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid rgba(255, 255, 255, 0.5);
                background: rgba(255, 255, 255, 0.6);
            }

            .student-navbar__logout {
                border: none;
                cursor: pointer;
                padding: 10px 20px;
                border-radius: 999px;
                background: rgba(47, 152, 140, 0.18);
                color: var(--student-primary);
                font-weight: 600;
                transition: background 0.2s ease;
            }

            .student-navbar__logout:hover {
                background: rgba(47, 152, 140, 0.28);
            }

            .student-main {
                display: flex;
                flex-direction: column;
                gap: clamp(32px, 6vw, 56px);
            }

            .student-section {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .student-section__header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 16px;
            }

            .student-section__title {
                margin: 0;
                font-size: clamp(1.2rem, 2.4vw, 1.8rem);
            }

            .student-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 12px 22px;
                border-radius: 999px;
                border: 1px solid transparent;
                font-weight: 500;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .student-button--primary {
                background: linear-gradient(120deg, var(--student-primary), var(--student-primary-soft));
                color: #ffffff;
                box-shadow: 0 20px 40px rgba(27, 119, 110, 0.22);
            }

            .student-button--outline {
                background: rgba(47, 152, 140, 0.08);
                border-color: rgba(47, 152, 140, 0.28);
                color: var(--student-primary);
            }

            .student-button:hover {
                transform: translateY(-2px);
            }

            .student-grid {
                display: grid;
                gap: clamp(18px, 3vw, 28px);
            }

            .student-grid--two {
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            }

            .student-grid--three {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            }

            .student-card {
                background: var(--student-surface);
                border-radius: var(--student-radius-lg);
                padding: clamp(20px, 3vw, 28px);
                box-shadow: 0 30px 60px rgba(34, 118, 108, 0.12);
                display: flex;
                flex-direction: column;
                gap: 12px;
                position: relative;
                overflow: hidden;
            }

            .student-card__subtitle {
                font-size: 0.9rem;
                color: var(--student-text-muted);
                margin: 0;
            }

            .student-card__title {
                margin: 0;
                font-size: 1.2rem;
                font-weight: 600;
            }

            .student-card__meta {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                font-size: 0.85rem;
                color: var(--student-text-muted);
            }

            .student-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 6px 14px;
                border-radius: 999px;
                font-size: 0.8rem;
                font-weight: 500;
                background: rgba(47, 152, 140, 0.16);
                color: var(--student-primary);
            }

            .student-alert {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 16px clamp(18px, 3vw, 28px);
                border-radius: var(--student-radius-md);
                background: rgba(47, 152, 140, 0.12);
                color: var(--student-primary);
                font-size: 0.95rem;
                box-shadow: 0 18px 36px rgba(27, 119, 110, 0.12);
            }

            .student-alert--warning {
                background: rgba(249, 178, 51, 0.16);
                color: #8a5500;
            }

            .student-alert--success {
                background: rgba(53, 182, 168, 0.16);
                color: var(--student-primary);
            }

            .student-alert--info {
                background: rgba(95, 106, 248, 0.12);
                color: var(--student-accent);
            }

            .student-alert__actions {
                display: inline-flex;
                align-items: center;
                gap: 12px;
            }

            footer.student-footer {
                text-align: center;
                font-size: 0.85rem;
                color: var(--student-text-muted);
            }

            @media (max-width: 960px) {
                .student-navbar {
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .student-navbar__links {
                    justify-content: center;
                }

                .student-navbar__actions {
                    flex-wrap: wrap;
                    justify-content: center;
                }
            }
        </style>
        @stack('styles')
    </head>
    <body>
        @php($user = auth()->user())
        @php($navAvatar = \App\Support\ProfileAvatar::forUser($user))
        @php($hasActivePackage = $hasActivePackage ?? ($studentHasActivePackage ?? false))
        <div class="student-shell">
            <header class="student-navbar">
                <a href="{{ route('student.dashboard') }}" class="student-navbar__brand">
                        <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" />
                </a>
                <nav class="student-navbar__links">
                    <a href="{{ route('student.dashboard') }}" class="student-navbar__link {{ request()->routeIs('student.dashboard') ? 'is-active' : '' }}">Beranda</a>
                    @if ($hasActivePackage)
                        <a href="{{ route('student.materials') }}" class="student-navbar__link {{ request()->routeIs('student.materials*') ? 'is-active' : '' }}">Materi</a>
                        <a href="{{ route('student.quiz') }}" class="student-navbar__link {{ request()->routeIs('student.quiz*') ? 'is-active' : '' }}">Quiz</a>
                        <a href="{{ route('student.schedule') }}" class="student-navbar__link {{ request()->routeIs('student.schedule') ? 'is-active' : '' }}">Jadwal</a>
                    @endif
                </nav>
                <div class="student-navbar__actions">
                    <a class="student-navbar__profile" href="{{ route('student.profile') }}">
                        <img class="student-navbar__avatar" src="{{ $navAvatar }}" alt="Foto profil {{ $user?->name ?? 'Siswa' }}" />
                        <span>{{ $user?->name ?? 'Siswa' }}</span>
                    </a>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="student-navbar__logout">Keluar</button>
                    </form>
                </div>
            </header>

            @if (session('subscription_notice'))
                <div class="student-alert student-alert--warning">
                    <span>{{ session('subscription_notice') }}</span>
                    <div class="student-alert__actions">
                        <a class="student-button student-button--primary" href="{{ route('packages.index') }}">Lihat paket</a>
                    </div>
                </div>
            @endif

            @if (session('subscription_success'))
                <div class="student-alert student-alert--success">
                    <span>{{ session('subscription_success') }}</span>
                    @if ($hasActivePackage)
                        <div class="student-alert__actions">
                            <a class="student-button student-button--primary" href="{{ route('student.materials') }}">
                                Buka Materi
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if (session('purchase_locked'))
                <div class="student-alert student-alert--info">
                    <span>{{ session('purchase_locked') }}</span>
                </div>
            @endif

            <main class="student-main">
                @yield('content')
            </main>

            <footer class="student-footer">© {{ now()->year }} MayClass. Portal siswa diperbarui otomatis.</footer>
        </div>
        @stack('scripts')
    </body>
</html>
