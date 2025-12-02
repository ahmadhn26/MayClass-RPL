<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard Tentor - MayClass')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --page-bg: #f5f7fb;
            --surface: #ffffff;
            --surface-muted: #f8fafc;
            --border-subtle: #e4e7ec;
            --sidebar-bg: #111c32;
            --text-main: #0f172a;
            --text-muted: #667085;
            --accent: #2563eb;
            --accent-muted: #e0e7ff;
            --success: #15803d;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--page-bg);
            color: var(--text-main);
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .dashboard-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 264px 1fr;
            gap: 32px;
            padding: 32px 40px;
            align-items: start;
        }

        .nav-panel {
            background: var(--sidebar-bg);
            border-radius: 24px;
            padding: 32px 24px;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 32px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.25);
            position: -webkit-sticky;
            position: sticky;
            top: 32px;
            align-self: start;
            height: calc(100vh - 64px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .navigation {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .nav-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            display: grid;
            place-items: center;
        }

        .nav-icon svg {
            width: 22px;
            height: 22px;
        }

        .nav-link[data-active='true'] {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        .nav-footer {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            padding-top: 20px;
        }

        .profile-summary {
            display: flex;
            align-items: center;
            gap: 14px;
            border-radius: 16px;
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.08);
            transition: background 0.2s ease;
        }

        .profile-summary img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.25);
        }

        .profile-summary:hover {
            background: rgba(255, 255, 255, 0.14);
        }

        .profile-summary small {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        .logout-btn {
            width: 100%;
        }

        .logout-btn button {
            width: 100%;
            padding: 12px 18px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: transparent;
            color: #fff;
            font: inherit;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .logout-btn button:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.55);
        }

        .main-area {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .main-header {
            background: var(--surface);
            border-radius: 24px;
            padding: 28px 32px;
            border: 1px solid var(--border-subtle);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
        }

        .header-intro h1 {
            margin: 6px 0 8px;
            font-size: 2rem;
        }

        .header-intro p {
            margin: 0;
            color: var(--text-muted);
        }

        .header-meta {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: flex-end;
        }

        .header-profile-link {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 2px solid rgba(226, 232, 240, 0.5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--surface-muted);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.18);
        }

        .header-profile-link img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .date-pill {
            padding: 10px 18px;
            border-radius: 999px;
            background: var(--surface-muted);
            border: 1px solid var(--border-subtle);
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .status-pill {
            padding: 8px 16px;
            border-radius: 999px;
            background: var(--accent-muted);
            color: var(--accent);
            font-weight: 700;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-pill::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
        }

        .page-wrapper {
            padding-bottom: 48px;
        }

        main {
            flex: 1;
        }

        .page-content {
            max-width: 1240px;
            width: 100%;
        }

        .flash-message {
            margin-bottom: 24px;
            padding: 16px 20px;
            border-radius: 14px;
            font-weight: 500;
            border: 1px solid var(--border-subtle);
            background: var(--surface);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .flash-message[data-variant='success'] {
            border-color: rgba(21, 128, 61, 0.2);
            color: var(--success);
        }

        .flash-message[data-variant='error'] {
            border-color: rgba(220, 38, 38, 0.25);
            color: #b91c1c;
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
        }

        .logo-area {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-area img {
            height: 32px;
            width: auto;
        }

        /* Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 995;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Hamburger Button */
        .hamburger-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 1002;
        }

        .hamburger-btn span {
            display: block;
            width: 24px;
            height: 2px;
            background-color: var(--text-main);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .hamburger-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        html,
        body {
            width: 100%;
        }

        @media (max-width: 1240px) {
            .dashboard-shell {
                grid-template-columns: 240px 1fr;
                padding: 28px;
            }
        }

        @media (max-width: 1024px) {
            .mobile-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 24px;
                width: 100%;
            }

            .dashboard-shell {
                display: block;
                /* Remove grid */
                padding: 20px;
                width: 100%;
                box-sizing: border-box;
            }

            .nav-panel {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
                max-width: 85vw;
                transform: translateX(-100%);
                border-radius: 0 24px 24px 0;
                z-index: 1000;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
                flex-direction: column;
                align-items: stretch;
                gap: 32px;
                overflow-y: auto;
                overscroll-behavior: contain;
                transition: transform 0.3s ease;
            }

            .nav-panel.active {
                transform: translateX(0);
            }

            .navigation {
                flex-direction: column;
                gap: 8px;
            }

            .nav-footer {
                display: flex;
                /* Show footer in sidebar */
                margin-top: auto;
            }

            .main-header {
                flex-direction: column;
            }

            .header-meta {
                align-items: flex-start;
            }
        }

        @media (max-width: 640px) {
            .dashboard-shell {
                padding: 16px;
            }

            .main-header {
                padding: 20px;
            }

            .header-intro h1 {
                font-size: 1.6rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="dashboard-shell">
        {{-- Mobile Header --}}
        <div class="mobile-header">
            <button class="hamburger-btn" id="tutorHamburger" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="logo-area">
                <span>Tutor Panel</span>
            </div>
        </div>

        {{-- Sidebar Overlay --}}
        <div class="sidebar-overlay" id="tutorOverlay"></div>

        <aside class="nav-panel" id="tutorSidebar">
            @php
                // Pastikan tutor ada untuk menghindari error pada method static
                $tutorSummaryAvatar = isset($tutor) ? \App\Support\ProfileAvatar::forUser($tutor) : asset('images/default-avatar.png');
            @endphp

            <nav class="navigation">
                @php
                    $currentRoute = request()->route() ? request()->route()->getName() : null;

                    // 1. Definisi Menu Default
                    $defaultMenuItems = [
                        [
                            'label' => 'Beranda',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>',
                            'route' => 'tutor.dashboard',
                            'patterns' => ['tutor.dashboard'],
                        ],
                        [
                            'label' => 'Jadwal Tentor',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                            'route' => 'tutor.schedule.index',
                            'patterns' => ['tutor.schedule.*'],
                        ],
                        [
                            'label' => 'Materi',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>',
                            'route' => 'tutor.materials.index',
                            'patterns' => ['tutor.materials.*'],
                        ],
                        [
                            'label' => 'Quiz',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>',
                            'route' => 'tutor.quizzes.index',
                            'patterns' => ['tutor.quizzes.*'],
                        ],
                    ];

                    // 2. Logika Penentuan Menu
                    if (isset($menuItems) && is_array($menuItems) && count($menuItems) > 0) {
                        $layoutMenuItems = $menuItems;
                    } else {
                        $layoutMenuItems = $defaultMenuItems;
                    }

                    // 3. Reindex array untuk keamanan loop
                    $layoutMenuItems = array_values($layoutMenuItems);
                @endphp

                @foreach ($layoutMenuItems as $item)
                    @php
                        $isActive = false;
                        // Pastikan patterns ada agar tidak error
                        $patterns = $item['patterns'] ?? [];

                        foreach ($patterns as $pattern) {
                            if ($currentRoute && \Illuminate\Support\Str::is($pattern, $currentRoute)) {
                                $isActive = true;
                                break;
                            }
                        }
                    @endphp
                    <a href="{{ route($item['route']) }}" class="nav-link" data-active="{{ $isActive ? 'true' : 'false' }}">
                        <span class="nav-icon" aria-hidden="true">{!! $item['icon'] !!}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
            <div class="nav-footer">
                <a class="profile-summary" href="{{ route('tutor.account.edit') }}" title="Kelola profil">
                    <img src="{{ $tutorSummaryAvatar }}" alt="Foto tutor" />
                    <div>
                        {{-- Gunakan optional chaining (?->) agar aman jika data kosong --}}
                        <strong>{{ $tutor?->name ?? 'Tutor MayClass' }}</strong>
                        <small>{{ $tutorProfile?->specializations ?? 'Mentor MayClass' }}</small>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="logout-btn">
                    @csrf
                    <button type="submit" title="Keluar dari dashboard">
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
        <div class="main-area">

            <main>
                <div class="page-wrapper">
                    <div class="page-content">
                        @if (session('status'))
                            <div class="flash-message" data-variant="success">{{ session('status') }}</div>
                        @endif
                        @if (session('alert'))
                            <div class="flash-message" data-variant="error">{{ session('alert') }}</div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile Sidebar Toggle
            const hamburger = document.getElementById('tutorHamburger');
            const sidebar = document.getElementById('tutorSidebar');
            const overlay = document.getElementById('tutorOverlay');

            function toggleSidebar() {
                hamburger.classList.toggle('active');
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');

                if (sidebar.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }

            if (hamburger) {
                hamburger.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Close sidebar when clicking a link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 1024) {
                        toggleSidebar();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>