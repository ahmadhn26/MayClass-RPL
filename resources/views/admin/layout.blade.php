<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard Admin - MayClass')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --surface: #ffffff;
            --surface-muted: #f1f5f9;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #6b7280;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar: #0f172a;
            --sidebar-muted: #1f2937;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--surface-muted);
            color: var(--text);
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .dashboard-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 32px;
            padding: 32px 40px;
            align-items: start;
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            background: var(--sidebar);
            color: white;
            position: sticky;
            top: 0;
            z-index: 990;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .mobile-brand {
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mobile-brand img {
            height: 32px;
            width: auto;
        }

        .hamburger-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 1002;
            /* Higher than sidebar */
        }

        .hamburger-btn span {
            display: block;
            width: 24px;
            height: 2px;
            background-color: white;
            transition: all 0.3s ease;
        }

        /* Hamburger Animation */
        .hamburger-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        .nav-panel {
            background: var(--sidebar);
            border-radius: 24px;
            padding: 28px 24px;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 24px;
            position: -webkit-sticky;
            position: sticky;
            top: 32px;
            align-self: start;
            height: calc(100vh - 64px);
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .navigation {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            transition: background 0.2s ease, color 0.2s ease;
            font-size: 0.95rem;
        }

        .nav-link[data-active='true'] {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        .nav-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            display: grid;
            place-items: center;
        }

        .nav-icon svg {
            width: 22px;
            height: 22px;
        }

        .nav-footer {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .profile-summary {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            color: inherit;
        }

        .profile-summary img {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .profile-summary strong {
            display: block;
            font-size: 1rem;
        }

        .profile-summary small {
            color: rgba(255, 255, 255, 0.7);
        }

        .logout-btn {
            width: 100%;
        }

        .logout-btn button {
            width: 100%;
            padding: 14px 20px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            background: #0d162d;
            color: #fff;
            font: inherit;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .logout-btn button:hover {
            background: #0f1c3d;
            border-color: rgba(255, 255, 255, 0.45);
            transform: translateY(-1px);
        }

        .main-area {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .main-header {
            background: var(--surface);
            border-radius: 20px;
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--border);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .header-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .header-profile-link {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 2px solid var(--border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--surface-muted);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
        }

        .header-profile-link img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .date-pill {
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--surface-muted);
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 0.9rem;
        }

        .page-wrapper {
            position: relative;
            padding-bottom: 48px;
        }

        main {
            flex: 1;
        }

        .page-content {
            display: block;
            max-width: 1240px;
        }

        .flash-message {
            margin-bottom: 24px;
            padding: 14px 18px;
            border-radius: 14px;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-weight: 500;
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

        @media (max-width: 1240px) {
            .dashboard-shell {
                grid-template-columns: 240px 1fr;
                padding: 24px;
            }
        }

        html,
        body {
            /* Global horizontal scroll prevention */
            width: 100%;
        }

        @media (max-width: 1024px) {
            .mobile-header {
                display: flex;
                width: 100%;
                box-sizing: border-box;
            }

            .dashboard-shell {
                display: block;
                /* Remove grid */
                padding: 16px;
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
                overflow-y: auto;
                /* Enable scrolling */
                overscroll-behavior: contain;
                /* Prevent body scroll chaining */
            }

            .nav-panel.active {
                transform: translateX(0);
            }

            .navigation {
                display: flex;
                flex-direction: column;
                /* Keep vertical */
                gap: 12px;
            }

            .nav-footer {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .main-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .header-meta {
                width: 100%;
                flex-wrap: wrap;
                gap: 10px;
            }
        }

        /* Global Delete Button */
        .btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: #ef4444;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Mobile Header -->
    <header class="mobile-header">
        <div class="mobile-brand">
            <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo">
            <span>Admin Panel</span>
        </div>
        <button class="hamburger-btn" id="adminHamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="adminOverlay"></div>

    <div class="dashboard-shell">
        <aside class="nav-panel" id="adminSidebar">
            <nav class="navigation">
                @php
                    $admin = auth()->user();
                    $currentRoute = request()->route() ? request()->route()->getName() : null;
                    $menuItems = [
                        [
                            'label' => 'Beranda',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 11.5 12 4l9 7.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 10v9h4v-5h6v5h4v-9" /></svg>',
                            'route' => 'admin.dashboard',
                            'patterns' => ['admin.dashboard'],
                        ],
                        [
                            'label' => 'Manajemen Jadwal',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3v2m8-2v2" /><rect width="18" height="16" x="3" y="5" rx="2" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18" /></svg>',
                            'route' => 'admin.schedules.index',
                            'patterns' => ['admin.schedules.*', 'admin.schedule.*'],
                        ],
                        [
                            'label' => 'Manajemen Siswa',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M7 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm10 0a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2 20a4.5 4.5 0 0 1 4.5-4.5H9a4.5 4.5 0 0 1 4.5 4.5v1H2zm9.5 1v-1A4.5 4.5 0 0 1 16 15.5h2.5A4.5 4.5 0 0 1 23 20v1z" /></svg>',
                            'route' => 'admin.students.index',
                            'patterns' => ['admin.students.*'],
                        ],
                        [
                            'label' => 'Manajemen Tentor',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 0-6 0 3 3 0 0 0 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 21a4.5 4.5 0 0 0-9 0Zm0 0H21a2 2 0 0 0 2-2v-1a8 8 0 0 0-8-8h-6a8 8 0 0 0-8 8v1a2 2 0 0 0 2 2h1.5" /></svg>',
                            'route' => 'admin.tentors.index',
                            'patterns' => ['admin.tentors.*'],
                        ],
                        [
                            'label' => 'Manajemen Paket',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7 12 3l9 4v10l-9 4-9-4z" /><path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 4 9-4" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10" /></svg>',
                            'route' => 'admin.packages.index',
                            'patterns' => ['admin.packages.*'],
                        ],
                        [
                            'label' => 'Mata Pelajaran',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>',
                            'route' => 'admin.subjects.index',
                            'patterns' => ['admin.subjects.*'],
                        ],
                        [
                            'label' => 'Manajemen Keuangan',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h14a2 2 0 0 1 2 2v8a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V9a2 2 0 0 1 2-2z" /><path stroke-linecap="round" stroke-linejoin="round" d="M18 11h3v4h-3a2 2 0 0 1 0-4z" /></svg>',
                            'route' => 'admin.finance.index',
                            'patterns' => ['admin.finance.*'],
                        ],
                        [
                            'label' => 'Manajemen Content',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>',
                            'route' => 'admin.landing-content.index',
                            'patterns' => ['admin.landing-content.*'],
                        ],
                    ];
                    $adminSummaryAvatar = \App\Support\ProfileAvatar::forUser($admin);
                @endphp
                @foreach ($menuItems as $item)
                    @php
                        $isActive = false;
                        foreach ($item['patterns'] as $pattern) {
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
                <a class="profile-summary" href="{{ route('admin.account.edit') }}" title="Kelola profil admin">
                    <img src="{{ $adminSummaryAvatar }}" alt="Foto admin" />
                    <div>
                        <strong>{{ $admin?->name ?? 'Admin MayClass' }}</strong>
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
                            <div class="flash-message">{{ session('status') }}</div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
    </div>

    {{-- Global Hidden Form for Deletion --}}
    <form id="global-delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile Sidebar Toggle
            const hamburger = document.getElementById('adminHamburger');
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminOverlay');

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

            // Close sidebar when clicking a link (optional)
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 1024) {
                        toggleSidebar();
                    }
                });
            });

            // Global Delete Handler
            document.body.addEventListener('click', function (e) {
                const button = e.target.closest('.btn-delete');
                if (button) {
                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const action = button.dataset.action; // URL to delete
                    const active = button.dataset.active === 'true'; // For students/others with active check

                    if (active) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menghapus',
                            text: `Tidak bisa menghapus "${name}" karena masih aktif/digunakan.`,
                            confirmButtonColor: '#0f766e',
                            confirmButtonText: 'Mengerti'
                        });
                    } else {
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: `Data "${name}" akan dihapus secara permanen.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const form = document.getElementById('global-delete-form');
                                form.action = action;
                                form.submit();
                            }
                        });
                    }
                }
            });

            // Show success/error messages from session
            @if(session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('status') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ session('error') }}",
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>