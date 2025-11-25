<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>@yield('title', 'Dashboard Tentor - MayClass')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        />
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
                position: sticky;
                top: 32px;
                align-self: start;
                min-height: calc(100vh - 64px);
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

            @media (max-width: 1240px) {
                .dashboard-shell {
                    grid-template-columns: 240px 1fr;
                    padding: 28px;
                }
            }

            @media (max-width: 1024px) {
                .dashboard-shell {
                    grid-template-columns: 1fr;
                    padding: 24px;
                }

                .nav-panel {
                    top: 24px;
                    z-index: 20;
                    flex-direction: row;
                    align-items: center;
                    gap: 20px;
                    overflow-x: auto;
                    min-height: auto;
                }

                .navigation {
                    flex-direction: row;
                    gap: 12px;
                }

                .nav-footer {
                    display: none;
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
                    padding: 20px;
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
            <aside class="nav-panel">
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
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" /></svg>',
                                'route' => 'tutor.dashboard',
                                'patterns' => ['tutor.dashboard'],
                            ],
                            [
                                'label' => 'Jadwal Tentor',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8M9 15h6" /><rect width="16" height="16" x="4" y="4" rx="3" /></svg>',
                                'route' => 'tutor.schedule.index',
                                'patterns' => ['tutor.schedule.*'],
                            ],
                            [
                                'label' => 'Materi',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5h9l5 5v9a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" /><path stroke-linecap="round" stroke-linejoin="round" d="M14 5v5h5" /></svg>',
                                'route' => 'tutor.materials.index',
                                'patterns' => ['tutor.materials.*'],
                            ],
                            [
                                'label' => 'Quiz',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6a4 4 0 0 1 4 4c0 1.5-.8 2.3-2 3.2-.7.5-1 1-.9 1.8m-.1 3h.01" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" /></svg>',
                                'route' => 'tutor.quizzes.index',
                                'patterns' => ['tutor.quizzes.*'],
                            ],
                        ];

                        // 2. Logika Penentuan Menu (Perbaikan Utama)
                        // Jika variabel $menuItems ada DAN berupa array, gunakan itu.
                        // Jika tidak, gunakan $defaultMenuItems.
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
                        <img
                            src="{{ $tutorSummaryAvatar }}"
                            alt="Foto tutor"
                        />
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
        @stack('scripts')
    </body>
</html>