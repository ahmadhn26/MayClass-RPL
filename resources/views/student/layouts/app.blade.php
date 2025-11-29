<!DOCTYPE html>
<html lang="id" data-role="student" data-page="{{ $page ?? '' }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ trim(($title ?? 'MayClass') . ' - MayClass') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
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
            position: relative;
            z-index: 1002;
        }

        .student-navbar__brand {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            font-weight: 600;
            color: var(--student-primary);
            flex-shrink: 0;
        }

        .student-navbar__brand img {
            width: 90px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
        }

        /* Hamburger menu button */
        .student-navbar__hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            z-index: 1005;
        }

        .student-navbar__hamburger span {
            width: 25px;
            height: 3px;
            background: var(--student-primary);
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .student-navbar__hamburger.active {
            position: fixed;
            right: 20px;
            top: 24px;
            /* Adjust based on navbar padding */
        }

        .student-navbar__hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .student-navbar__hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .student-navbar__hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
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
            background: var(--student-primary);
        }

        .student-navbar__actions {
            display: inline-flex;
            align-items: center;
            gap: 16px;
        }

        /* Desktop nav-actions - visible on desktop, hidden on mobile */
        .student-navbar__actions--desktop {
            display: flex;
        }

        /* Mobile nav-actions (inside menu) - hidden on desktop */
        .student-navbar__links .student-navbar__actions--mobile {
            display: none;
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

        /* Mobile Menu Styles */
        .student-mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            padding: 80px 24px 24px;
            gap: 24px;
            box-shadow: -4px 0 24px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease;
            z-index: 1003;
            overflow-y: auto;
        }

        .student-mobile-menu.active {
            right: 0;
        }

        .student-mobile-menu__links {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .student-mobile-menu__actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 8px;
            padding-top: 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .student-mobile-menu__actions .student-navbar__profile {
            width: 100%;
            justify-content: flex-start;
        }

        .student-mobile-menu__actions .student-navbar__logout {
            width: 100%;
        }

        @media (max-width: 768px) {
            .student-navbar {
                padding: 12px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .student-navbar__brand {
                flex-shrink: 0;
            }

            .student-navbar__hamburger {
                display: flex;
                margin-left: auto;
                padding: 8px;
            }

            /* Hide desktop elements on mobile */
            .student-navbar__actions--desktop,
            .student-navbar__links {
                display: none !important;
            }

            .student-navbar__brand img {
                width: 60px;
                height: 48px;
            }

            /* Mobile menu link styles */
            .student-navbar__link {
                font-size: 1.1rem;
                padding: 8px 0;
                width: 100%;
            }

            .student-navbar__link.is-active::after {
                bottom: 0;
            }
        }

        /* Overlay for mobile menu */
        .student-navbar__overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .student-navbar__overlay.active {
            display: block;
            opacity: 1;
        }
    </style>
    @stack('styles')
</head>

<body>
    @php($user = auth()->user())
    @php($navAvatar = \App\Support\ProfileAvatar::forUser($user))
    @php($hasActivePackage = $hasActivePackage ?? ($studentHasActivePackage ?? false))
    <div class="student-shell">
        <!-- Overlay for mobile menu -->
        <div class="student-navbar__overlay" id="navOverlay"></div>

        <!-- Mobile Menu (Moved outside navbar to fix position:fixed relative to viewport) -->
        <div class="student-mobile-menu" id="mobileMenu">
            <nav class="student-mobile-menu__links">
                <a href="{{ route('student.dashboard') }}"
                    class="student-navbar__link {{ request()->routeIs('student.dashboard') ? 'is-active' : '' }}">Beranda</a>
                @if ($hasActivePackage)
                    <a href="{{ route('student.materials') }}"
                        class="student-navbar__link {{ request()->routeIs('student.materials*') ? 'is-active' : '' }}">Materi</a>
                    <a href="{{ route('student.quiz') }}"
                        class="student-navbar__link {{ request()->routeIs('student.quiz*') ? 'is-active' : '' }}">Quiz</a>
                    <a href="{{ route('student.schedule') }}"
                        class="student-navbar__link {{ request()->routeIs('student.schedule') ? 'is-active' : '' }}">Jadwal</a>
                @endif
            </nav>

            <div class="student-mobile-menu__actions">
                <a class="student-navbar__profile" href="{{ route('student.profile') }}">
                    <img class="student-navbar__avatar" src="{{ $navAvatar }}"
                        alt="Foto profil {{ $user?->name ?? 'Siswa' }}" />
                    <span>{{ $user?->name ?? 'Siswa' }}</span>
                </a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="student-navbar__logout">Keluar</button>
                </form>
            </div>
        </div>

        <header class="student-navbar">
            <a href="{{ route('student.dashboard') }}" class="student-navbar__brand">
                <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" />
            </a>

            <!-- Desktop Navigation -->
            <nav class="student-navbar__links">
                <a href="{{ route('student.dashboard') }}"
                    class="student-navbar__link {{ request()->routeIs('student.dashboard') ? 'is-active' : '' }}">Beranda</a>
                @if ($hasActivePackage)
                    <a href="{{ route('student.materials') }}"
                        class="student-navbar__link {{ request()->routeIs('student.materials*') ? 'is-active' : '' }}">Materi</a>
                    <a href="{{ route('student.quiz') }}"
                        class="student-navbar__link {{ request()->routeIs('student.quiz*') ? 'is-active' : '' }}">Quiz</a>
                    <a href="{{ route('student.schedule') }}"
                        class="student-navbar__link {{ request()->routeIs('student.schedule') ? 'is-active' : '' }}">Jadwal</a>
                @endif
            </nav>

            <!-- Desktop actions -->
            <div class="student-navbar__actions student-navbar__actions--desktop">
                <a class="student-navbar__profile" href="{{ route('student.profile') }}">
                    <img class="student-navbar__avatar" src="{{ $navAvatar }}"
                        alt="Foto profil {{ $user?->name ?? 'Siswa' }}" />
                    <span>{{ $user?->name ?? 'Siswa' }}</span>
                </a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="student-navbar__logout">Keluar</button>
                </form>
            </div>

            <!-- Hamburger button -->
            <button class="student-navbar__hamburger" id="hamburger" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
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

    <script>
        // Hamburger menu toggle
        document.addEventListener('DOMContentLoaded', function () {
            const hamburger = document.getElementById('hamburger');
            const mobileMenu = document.getElementById('mobileMenu');
            const navOverlay = document.getElementById('navOverlay');

            // Function to lock body scroll
            function lockBodyScroll() {
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
            }

            // Function to unlock body scroll
            function unlockBodyScroll() {
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
            }

            if (hamburger && mobileMenu && navOverlay) {
                // Toggle menu
                hamburger.addEventListener('click', function () {
                    const isActive = hamburger.classList.toggle('active');
                    mobileMenu.classList.toggle('active');
                    navOverlay.classList.toggle('active');

                    // Lock/unlock body scroll based on menu state
                    if (isActive) {
                        lockBodyScroll();
                    } else {
                        unlockBodyScroll();
                    }
                });

                // Close menu when clicking overlay
                navOverlay.addEventListener('click', function () {
                    hamburger.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    navOverlay.classList.remove('active');
                    unlockBodyScroll();
                });

                // Close menu when clicking a link
                const links = mobileMenu.querySelectorAll('.student-navbar__link');
                links.forEach(link => {
                    link.addEventListener('click', function () {
                        hamburger.classList.remove('active');
                        mobileMenu.classList.remove('active');
                        navOverlay.classList.remove('active');
                        unlockBodyScroll();
                    });
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>