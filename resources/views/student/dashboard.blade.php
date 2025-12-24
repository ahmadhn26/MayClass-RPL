@extends('student.layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
    <style>
        :root {
            /* Color Palette */
            --primary: #0f766e;
            --primary-light: #e6fffa;
            --primary-dark: #0f5132;
            --surface: #ffffff;
            --background: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;

            /* Dimensions & Spacing */
            --sidebar-width: 380px;
            --radius: 16px;

            /* Effects */
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Layout Full Width */
        .dashboard-container {
            width: 100%;
            padding: 0 32px;
            display: grid;
            gap: 32px;
        }

        /* Grid Layout: Kiri (Konten Elastis) & Kanan (Sidebar Fixed) */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 1fr var(--sidebar-width);
            gap: 32px;
            align-items: start;
        }

        /* --- Welcome Hero Card --- */
        .hero-card {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            color: white;
            border-radius: var(--radius);
            padding: 40px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            display: flex;
            flex-direction: column;
            justify-content: center;

            /* PERBAIKAN: Menambahkan jarak ke bawah agar tidak dempet dengan stats */
            margin-bottom: 32px;
        }

        /* Pattern Dekorasi Halus */
        .hero-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-eyebrow {
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            opacity: 0.9;
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 14px;
            line-height: 1.2;
        }

        .hero-desc {
            font-size: 1.05rem;
            opacity: 0.95;
            margin: 0 0 28px;
            max-width: 700px;
            line-height: 1.6;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-hero {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            backdrop-filter: blur(4px);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hero:hover {
            background: white;
            color: var(--primary);
            border-color: white;
            transform: translateY(-2px);
        }

        /* --- Stats Grid --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--surface);
            padding: 24px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* --- Content Lists --- */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 40px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .link-more {
            font-size: 0.9rem;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .link-more:hover {
            background: var(--primary-light);
        }

        /* Grid Kartu Materi & Kuis (Responsive Filling) */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        .content-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
            text-decoration: none;
            height: 100%;
        }

        .content-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: #cbd5e1;
        }

        .card-tag {
            align-self: flex-start;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 6px 12px;
            border-radius: 99px;
            letter-spacing: 0.03em;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            line-height: 1.4;
        }

        .card-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .card-meta {
            margin-top: 8px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* --- Sidebar Components --- */
        .sidebar-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
            position: sticky;
            top: 20px;
        }

        .sidebar-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .sidebar-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        /* Active Package Widget */
        .pkg-info {
            background: var(--primary-light);
            border-radius: 12px;
            padding: 16px;
            border: 1px solid rgba(15, 118, 110, 0.1);
        }

        .pkg-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-dark);
            display: block;
            margin-bottom: 4px;
        }

        .pkg-meta {
            font-size: 0.85rem;
            color: var(--primary);
            font-weight: 500;
        }

        /* Schedule Timeline Look */
        .schedule-list {
            position: relative;
            padding-left: 16px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Garis Vertikal Timeline */
        .schedule-list::before {
            content: '';
            position: absolute;
            top: 8px;
            bottom: 0;
            left: 0;
            width: 2px;
            background: var(--border);
        }

        .schedule-item {
            position: relative;
            padding-left: 20px;
        }

        /* Titik Timeline */
        .schedule-item::before {
            content: '';
            position: absolute;
            left: -21px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--surface);
            border: 2px solid var(--primary);
            z-index: 2;
        }

        .schedule-date {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            margin-bottom: 4px;
            display: block;
        }

        .schedule-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
            margin: 0 0 4px;
            line-height: 1.4;
        }

        .schedule-detail {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px;
            background: var(--surface);
            border: 1px dashed var(--border);
            border-radius: var(--radius);
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Inactive State Centered */
        .inactive-wrapper {
            display: flex;
            justify-content: center;
            padding: 40px 0;
        }

        .inactive-card {
            background: var(--surface);
            padding: 48px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-md);
            max-width: 800px;
            width: 100%;
            text-align: center;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 40px;
            text-align: left;
        }

        .step-box {
            background: var(--background);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .step-num {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            opacity: 0.2;
            margin-bottom: 8px;
            line-height: 1;
        }

        .step-title {
            font-weight: 700;
            color: var(--text-main);
            display: block;
            margin-bottom: 4px;
        }

        .step-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .dashboard-container { padding: 0 20px; }
            .dashboard-layout { 
                grid-template-columns: 1fr; 
                gap: 40px;
            }
            .sidebar-content { position: static; }
            .steps-grid { grid-template-columns: 1fr; }

            /* Horizontal scroll for cards on mobile/tablet */
            /* FIX: Vertical Stacking for Cards (Materials/Quizzes) */
            .cards-grid {
                display: flex !important;
                flex-direction: column !important;
                overflow-x: visible !important;
                overflow-y: visible !important;
                scroll-snap-type: none !important;
                gap: 16px !important;
                padding: 0 !important;
            }

            .cards-grid::-webkit-scrollbar {
                display: none;
            }

            .content-card {
                flex: none !important;
                width: 100% !important;
                max-width: 100% !important;
                display: flex;
                flex-direction: column;
                /* Mimic Image 2 style (Left Border Accent) */
                border-left: 4px solid var(--primary);
                padding: 20px !important;
            }
        }

        /* Tablet specific adjustments */
        @media (min-width: 641px) and (max-width: 1024px) {
            .content-card {
                flex: 0 0 48%;
                max-width: 48%;
            }
        }

        /* Mobile specific optimizations */
        @media (max-width: 768px) {
            .dashboard-container { padding: 0 20px !important; }

            .hero-card { 
                padding: 24px !important; 
                margin-bottom: 24px !important;
            }

            .hero-title { 
                font-size: 1.5rem !important; 
                line-height: 1.3 !important;
            }

            .hero-desc {
                font-size: 0.95rem !important;
                margin-bottom: 20px !important;
            }

            .hero-actions { 
                flex-direction: column !important; 
                gap: 12px !important;
            }

            .btn-hero { 
                width: 100% !important; 
                justify-content: center !important;
                min-height: 48px !important;
            }

            /* FIX: Vertical Stacking for Stats */
            .stats-grid { 
                display: flex !important;
                flex-direction: column !important;
                gap: 16px !important; 
                margin-bottom: 32px !important;
                grid-template-columns: none !important; /* Reset grid */
            }

            .stat-card {
                padding: 24px !important;
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: center !important;
                width: 100% !important;
            }

            .stat-value {
                font-size: 1.75rem !important;
                order: 2 !important;
                margin: 0 !important;
            }

            .stat-label {
                font-size: 1rem !important;
                order: 1 !important;
            }

            .section-header {
                margin-top: 32px !important;
                margin-bottom: 20px !important;
            }

            .section-title {
                font-size: 1.2rem !important;
            }
            
            /* Sidebar adjustments for mobile if hidden/moved */
            .sidebar-content {
                display: none !important; 
            }
        }

        /* Desktop - revert to grid (>1024px) */
        @media (min-width: 1025px) {
            .cards-grid {
                display: grid !important;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }
    </style>
@endpush

@php($user = auth()->user())
@php($materialsLink = $materialsLink ?? config('mayclass.links.materials_drive'))
@php($quizLink = $quizLink ?? config('mayclass.links.quiz_platform'))
@php($hasActivePackage = $hasActivePackage ?? ($studentHasActivePackage ?? false))

@section('content')

@if ($hasActivePackage)
<div class="dashboard-container">

    <div class="dashboard-layout">
        {{-- LEFT COLUMN: Main Content (Elastis) --}}
        <main>
            {{-- 1. Hero Welcome --}}
            <div class="hero-card">
                <div class="hero-content">
                    <span class="hero-eyebrow">Dashboard Siswa</span>
                    <h1 class="hero-title">Hai, {{ $user?->name ?? 'Siswa' }}!</h1>
                    <p class="hero-desc">
                        Paket belajarmu aktif. Siap untuk meningkatkan prestasi hari ini?
                        Akses materi, kerjakan kuis, atau cek jadwalmu sekarang.
                    </p>
                </div>
            </div>

            {{-- 2. Quick Stats --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-value">{{ number_format($metrics['materials_total']) }}</span>
                    <span class="stat-label">Materi Tersedia</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">{{ number_format($metrics['quizzes_total']) }}</span>
                    <span class="stat-label">Kuis Latihan</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">{{ number_format($metrics['upcoming_total']) }}</span>
                    <span class="stat-label">Sesi Terjadwal</span>
                </div>
            </div>

            {{-- 3. Materials Section --}}
            <div class="section-header">
                <h2 class="section-title">Materi Terbaru</h2>
                <a href="{{ route('student.materials') }}" class="link-more">Lihat Semua &rarr;</a>
            </div>

            @if ($recentMaterials->isNotEmpty())
                <div class="cards-grid">
                    @foreach ($recentMaterials as $material)
                        <a href="{{ route('student.materials') }}?preview={{ $material['id'] }}" class="content-card">
                            <span class="card-tag">{{ $material['subject'] }}</span>
                            <h3 class="card-title">📁 {{ $material['title'] }}</h3>
                            <p class="card-desc">{{ $material['summary'] }}</p>
                            <div class="card-meta">
                                <span>Level {{ $material['level'] }}</span>
                                <span>&bull;</span>
                                <span>{{ $material['item_count'] }} Materi</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state">Belum ada materi baru yang diterbitkan.</div>
            @endif

            {{-- 4. Quizzes Section --}}
            <div class="section-header">
                <h2 class="section-title">Kuis Latihan</h2>
                <a href="{{ route('student.quiz') }}" class="link-more">Lihat Semua &rarr;</a>
            </div>

            @if ($recentQuizzes->isNotEmpty())
                <div class="cards-grid">
                    @foreach ($recentQuizzes as $quiz)
                        <a href="{{ $quiz['link'] ?? $quizLink }}" target="_blank" rel="noopener" class="content-card">
                            <span class="card-tag" style="background:#fff7ed; color:#c2410c;">{{ $quiz['questions'] }}
                                Soal</span>
                            <h3 class="card-title">{{ $quiz['title'] }}</h3>
                            <p class="card-desc">{{ $quiz['summary'] }}</p>
                            <div class="card-meta">
                                <span>{{ $quiz['duration'] }}</span>
                                @if(!empty($quiz['levels']))
                                    <span>&bull;</span>
                                    <span>{{ implode(', ', $quiz['levels']) }}</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state">Kuis belum tersedia saat ini.</div>
            @endif

        </main>

        {{-- RIGHT COLUMN: Sidebar (Fixed Width) --}}
        <aside class="sidebar-content">

            {{-- Active Package --}}
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <h3 class="sidebar-title">PAKET AKTIF</h3>
                </div>
                <div class="pkg-info">
                    <span class="pkg-name">{{ $activePackage['title'] }}</span>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="pkg-meta">{{ $activePackage['period'] }}</span>
                        <span
                            style="font-size: 0.75rem; background: #0f766e; color: white; padding: 2px 8px; border-radius: 4px;">{{ $activePackage['status'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Schedule Timeline --}}
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <h3 class="sidebar-title">JADWAL TERDEKAT</h3>
                    <a href="{{ route('student.schedule') }}" class="link-more" style="font-size: 0.8rem;">Semua</a>
                </div>

                @php($upcomingSessions = collect($schedule['upcoming'] ?? [])->take(4))

                                    @if ($upcomingSessions->isNotEmpty())
                                        <div class="schedule-list">
                                            @foreach ($upcomingSessions as $session)
                                                <div class="schedule-item">
                                                    <span class="schedule-date">{{ $session['date'] }}</span>
                                                    <h4 class="schedule-title">{{ $session['title'] }}</h4>
                                                    <p class="schedule-detail">{{ $session['subject'] ?? $session['category'] }}</p>
                                                    <p class="schedule-detail" style="margin-top: 2px;">🕒 {{ $session['time'] }} &bull;
                                                        {{ $session['mentor'] }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div style="text-align: center; padding: 20px 0; color: var(--text-muted); font-size: 0.9rem;">
                                            Belum ada jadwal sesi mendatang.
                                        </div>
                                    @endif
                                </div>
                            </aside>
                        </div>
                    </div>

                @else
{{-- USER BELUM PUNYA PAKET ATAU DINONAKTIFKAN --}}

@if($isDeactivated ?? false)
    {{-- DEACTIVATED ACCOUNT WARNING --}}
    <div style="max-width: 700px; margin: 60px auto; padding: 0 24px;">
        <div style="background: #fff; border-radius: 20px; padding: 48px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 2px solid #fecaca;">
            
            {{-- Warning Icon --}}
            <div style="width: 100px; height: 100px; margin: 0 auto 24px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 56px; height: 56px; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            {{-- Title --}}
            <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin: 0 0 16px; line-height: 1.2;">
                Akun Anda Telah Dinonaktifkan
            </h1>
            
            {{-- Description --}}
            <p style="color: #64748b; font-size: 1.0625rem; line-height: 1.7; margin: 0 0 12px; max-width: 500px; margin-left: auto; margin-right: auto;">
                Paket belajar <strong style="color: #0f766e;">{{ $lastEnrollment->package->detail_title ?? 'Anda' }}</strong> telah dinonaktifkan oleh admin.
            </p>
            
            <p style="color: #64748b; font-size: 1rem; line-height: 1.6; margin: 0 0 32px;">
                Silakan hubungi admin untuk klarifikasi lebih lanjut mengenai status akun Anda.
            </p>
            
            {{-- WhatsApp Button --}}
            <a href="{{ $whatsappAdminLink }}" target="_blank" rel="noopener" 
               style="display: inline-flex; align-items: center; gap: 10px; background: #25D366; color: white; padding: 16px 32px; border-radius: 999px; font-weight: 600; font-size: 1rem; text-decoration: none; box-shadow: 0 8px 24px rgba(37, 211, 102, 0.35); transition: all 0.2s ease; border: none;">
                <svg style="width: 24px; height: 24px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <span>Hubungi Admin via WhatsApp</span>
            </a>
            
            {{-- Package Info Footer --}}
            <div style="margin-top: 40px; padding-top: 32px; border-top: 1px solid #e2e8f0;">
                <p style="font-size: 0.875rem; color: #94a3b8; margin: 0 0 4px;">Paket Terakhir</p>
                <p style="font-size: 0.9375rem; font-weight: 600; color: #475569; margin: 0 0 8px;">
                    {{ $lastEnrollment->package->detail_title ?? '-' }}
                </p>
                @if($lastEnrollment->starts_at || $lastEnrollment->ends_at)
                    <p style="font-size: 0.8125rem; color: #94a3b8; margin: 0;">
                        Periode: 
                        {{ $lastEnrollment->starts_at ? \Carbon\Carbon::parse($lastEnrollment->starts_at)->format('d M Y') : '-' }} 
                        - 
                        {{ $lastEnrollment->ends_at ? \Carbon\Carbon::parse($lastEnrollment->ends_at)->format('d M Y') : '-' }}
                    </p>
                @endif
            </div>
        </div>
    </div>

@else
    {{-- USER BELUM PUNYA PAKET (EXISTING UI) --}}
    <div class="inactive-wrapper">
        <div class="inactive-card">
            <h1 style="margin: 0 0 12px; font-size: 2.2rem; font-weight: 700; color: var(--text-main);">Halo,
                {{ $user?->name ?? 'Siswa' }}!</h1>
            <p
                style="color: var(--text-muted); margin-bottom: 32px; max-width: 640px; margin-left: auto; margin-right: auto; line-height: 1.6; font-size: 1.05rem;">
                Akunmu sudah aktif, tetapi kamu belum memiliki paket belajar. Yuk, pilih paket favoritmu untuk membuka akses
                ke materi, kuis, dan jadwal eksklusif MayClass.
            </p>

            <a href="{{ route('packages.index') }}"
                style="display: inline-block; background: var(--primary); color: white; padding: 14px 36px; border-radius: 99px; font-weight: 600; text-decoration: none; box-shadow: 0 10px 20px -5px rgba(15, 118, 110, 0.4); transition: transform 0.2s;">
                Lihat Pilihan Paket
            </a>

            <div class="steps-grid">
                <div class="step-box">
                    <div class="step-num">01</div>
                    <span class="step-title">Pilih Paket</span>
                    <span class="step-desc">Bandingkan fitur dan harga di katalog kami.</span>
                </div>
                <div class="step-box">
                    <div class="step-num">02</div>
                    <span class="step-title">Bayar</span>
                    <span class="step-desc">Transfer dan unggah bukti pembayaran.</span>
                </div>
                <div class="step-box">
                    <div class="step-num">03</div>
                    <span class="step-title">Belajar</span>
                    <span class="step-desc">Admin verifikasi, akses langsung terbuka.</span>
                </div>
            </div>
        </div>
    </div>
@endif
@endif

@endsection