@extends('tutor.layout')

@section('title', 'Jadwal Mengajar - MayClass')

@push('styles')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            --primary-solid: #0f766e;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            --radius-lg: 24px;
            --radius-md: 16px;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-card: #ffffff;
            --bg-page: #f8fafc;
        }

        .schedule-content {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        /* --- HERO SECTION (Style Hijau Teal + Lingkaran) --- */
        .hero-card {
            position: relative;
            background: var(--primary-gradient);
            border-radius: var(--radius-lg);
            padding: 48px;
            color: white;
            display: grid;
            gap: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .hero-card::before,
        .hero-card::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            z-index: 1;
        }

        .hero-card::before {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -50px;
        }

        .hero-card::after {
            width: 200px;
            height: 200px;
            bottom: -50px;
            left: 10%;
        }

        .hero-header {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            flex-wrap: wrap;
        }

        .hero-content h1 {
            margin: 0 0 8px 0;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero-content p {
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            max-width: 600px;
            line-height: 1.6;
        }

        .metrics-row {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .metric-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 16px 24px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            min-width: 140px;
            transition: all 0.3s ease;
        }

        .metric-pill:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.15);
        }

        .metric-pill span {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .metric-pill strong {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
            color: white;
        }

        /* --- LAYOUT UTAMA --- */
        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 32px;
            align-items: start;
        }

        .main-schedule {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* --- HEADER JUDUL (Perbaikan agar angka turun ke bawah) --- */
        .section-header {
            display: flex;
            flex-direction: column;
            /* Ini kuncinya: susun ke bawah */
            gap: 4px;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            line-height: 1.2;
        }

        .count-badge {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Garis kecil di samping angka */

        /* --- EMPTY STATE (Kotak Kosong) --- */
        .empty-state {
            text-align: center;
            padding: 32px;
            /* Padding lebih kecil biar ga terlalu tinggi */
            background: #f8fafc;
            border-radius: 16px;
            border: 2px dashed #cbd5e1;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            min-height: 140px;
            /* Tinggi minimum yang pas */
            justify-content: center;
        }

        .empty-state svg {
            color: #94a3b8;
        }

        /* --- SESSION CARD --- */
        .session-card {
            background: var(--bg-card);
            border-radius: var(--radius-md);
            padding: 24px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 24px;
            overflow: hidden;
            margin-bottom: 24px;
            /* Jarak antar kartu */
        }

        .session-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-shadow-hover);
            border-color: #cbd5e1;
        }

        /* Garis warna di kiri kartu */
        .session-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: currentColor;
        }

        .session-card.today {
            border-color: var(--primary-solid);
            /* Warna Hijau Teal */
            background: #f0fdfa;
            /* Background Hijau Muda */
            color: var(--primary-solid);
        }

        .date-box {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 90px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .session-card.today .date-box {
            background: var(--primary-solid);
            /* Kotak tanggal jadi hijau solid */
            color: white;
            border: none;
        }

        .date-box .day {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        .session-card.today .date-box .day {
            color: rgba(255, 255, 255, 0.8);
        }

        .date-box .date {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
        }

        .session-card.today .date-box .date {
            color: white;
        }

        .date-box .month {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .session-card.today .date-box .month {
            color: rgba(255, 255, 255, 0.8);
        }

        .session-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .session-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .session-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            line-height: 1.4;
        }

        .time-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            padding: 8px 16px;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
        }

        .session-card.today .time-badge {
            background: var(--primary-solid);
            color: white;
        }

        .meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
            background: white;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .meta-item svg {
            width: 16px;
            height: 16px;
            color: #94a3b8;
        }

        /* --- SIDEBAR HISTORY --- */
        .history-sidebar {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 32px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            height: fit-content;
        }

        .history-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 16px;
        }

        .history-item {
            display: flex;
            gap: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .history-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .history-date {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-muted);
            min-width: 60px;
        }

        .history-content h4 {
            margin: 0 0 4px 0;
            font-size: 0.95rem;
            color: var(--text-main);
            font-weight: 600;
        }

        .history-status {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 99px;
            display: inline-block;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 1024px) {
            .schedule-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .session-card {
                grid-template-columns: 1fr;
            }

            .date-box {
                flex-direction: row;
                gap: 12px;
                padding: 12px;
                justify-content: flex-start;
            }

            .date-box .date {
                font-size: 1.25rem;
                margin: 0;
            }

            .hero-card {
                padding: 24px;
                /* Reduced padding */
            }

            .hero-content h1 {
                font-size: 1.75rem;
            }

            /* Metrics Row Mobile Optimization */
            .metrics-row {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                /* Force 3 columns */
                gap: 8px;
            }

            .metric-pill {
                min-width: 0;
                /* Allow shrinking */
                padding: 12px 8px;
                /* Compact padding */
                align-items: center;
                text-align: center;
            }

            .metric-pill span {
                font-size: 0.7rem;
                white-space: nowrap;
            }

            .metric-pill strong {
                font-size: 1.25rem;
            }
        }

        /* Zoom Button Styling */
        .zoom-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #2d8cff;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }

        .zoom-btn:hover {
            background: #1a73e8;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(45, 140, 255, 0.3);
        }

        .zoom-btn svg {
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    <div class="schedule-content">

        {{-- 1. HERO SECTION --}}
        <section class="hero-card">
            <div class="hero-header">
                <div class="hero-content">
                    <h1>Agenda Mengajar</h1>
                    <p>Kelola dan pantau jadwal sesi kelas Anda. Pastikan hadir tepat waktu untuk memberikan pengalaman
                        belajar terbaik.</p>
                </div>
            </div>
            <div class="metrics-row">
                <div class="metric-pill">
                    <span>Sesi Mendatang</span>
                    <strong>{{ $metrics['upcoming'] }}</strong>
                </div>
                <div class="metric-pill">
                    <span>Total Sesi</span>
                    <strong>{{ $metrics['total'] }}</strong>
                </div>
                <div class="metric-pill">
                    <span>Riwayat</span>
                    <strong>{{ $metrics['history'] }}</strong>
                </div>
            </div>
        </section>

        <div class="schedule-grid">

            {{-- 2. KOLOM JADWAL (KIRI) --}}
            <div class="main-schedule">

                {{-- JADWAL HARI INI --}}
                <div class="section-container">
                    <div class="section-header">
                        <h2 class="section-title">Jadwal Hari Ini</h2>
                        <span class="count-badge">{{ $todaySessions->count() }} Sesi</span>
                    </div>

                    @if ($todaySessions->isEmpty())
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="width: 40px; height: 40px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Tidak ada jadwal mengajar hari ini.</p>
                        </div>
                    @else
                        <div class="session-list">
                            @foreach ($todaySessions as $session)
                                @php $date = $session['start_at']; @endphp
                                <div class="session-card today">
                                    <div class="date-box">
                                        <span class="day">HARI INI</span>
                                        <span class="date">{{ $date ? $date->format('d') : '-' }}</span>
                                        <span class="month">{{ $date ? $date->locale('id')->isoFormat('MMM') : '-' }}</span>
                                    </div>

                                    <div class="session-details">
                                        <div class="session-header">
                                            <h3 class="session-title">{{ $session['title'] }}</h3>
                                            <div class="time-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" width="16" height="16">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $session['time_range'] }}
                                            </div>
                                        </div>

                                        <div class="meta-row">
                                            <div class="meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                {{ $session['package'] }}
                                            </div>
                                            <div class="meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                {{ $session['subject'] }}
                                            </div>
                                            <div class="meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $session['location'] }}
                                            </div>
                                            <div class="meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                {{ $session['participant_summary'] }}
                                            </div>
                                        </div>

                                        @if(str_contains($session['location'], 'Online') && !empty($session['zoom_link']))
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $hasStarted = $session['start_at'] && $session['start_at']->lte($now);
                                            @endphp
                                            <div style="margin-top: 12px; display: flex; align-items: center; gap: 8px;">
                                                {{-- Logic: Enabled only if H-5 hours --}}
                                                @php
                                                    $joinableTime = $session['start_at']->copy()->subHours(5);
                                                    $isJoinable = $now->greaterThanOrEqualTo($joinableTime);
                                                @endphp

                                                @if($isJoinable)
                                                    <a href="{{ $session['zoom_link'] }}" target="_blank" rel="noopener" class="zoom-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M15.5 5H19a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3.5m-11-4v-8a2 2 0 0 1 2-2H11a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6.5a2 2 0 0 1-2-2z">
                                                            </path>
                                                            <polyline points="15 10 20 7 20 17 15 14"></polyline>
                                                        </svg>
                                                        Join Zoom
                                                    </a>
                                                    
                                                    {{-- Copy Link Button --}}
                                                    <button type="button" class="zoom-btn" onclick="copyToClipboard('{{ $session['zoom_link'] }}')" 
                                                        style="background: white; color: #2d8cff; border: 1px solid #2d8cff; padding: 10px;" title="Salin Link">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button disabled class="zoom-btn"
                                                        style="background: #cbd5e1; color: #64748b; cursor: not-allowed; opacity: 0.8;"
                                                        title="Link Zoom akan aktif 5 jam sebelum sesi dimulai">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <polyline points="12 6 12 12 16 14"></polyline>
                                                        </svg>
                                                        Zoom Aktif {{ $joinableTime->locale('id')->diffForHumans() }}
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- JADWAL MENDATANG --}}
                <div class="section-container" style="margin-top: 24px;">
                    <div class="section-header">
                        <h2 class="section-title">Jadwal Mendatang</h2>
                        <span class="count-badge">{{ $futureSessions->count() }} Sesi</span>
                    </div>

                    @if ($futureSessions->isEmpty())
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="width: 40px; height: 40px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <p>Belum ada jadwal mendatang lainnya.</p>
                        </div>
                    @else
                        <div class="session-list" id="future-sessions-list">
                            @foreach ($futureSessions as $index => $session)
                                @php $date = $session['start_at']; @endphp
                                <div class="session-card" data-session-index="{{ $index }}"
                                    style="{{ $index >= 10 ? 'display: none;' : '' }}">
                                    <div class="date-box">
                                        <span class="day">{{ $date ? $date->locale('id')->isoFormat('ddd') : '-' }}</span>
                                        <span class="date">{{ $date ? $date->format('d') : '-' }}</span>
                                        <span class="month">{{ $date ? $date->locale('id')->isoFormat('MMM Y') : '-' }}</span>
                                    </div>

                                    <div class="session-details">
                                        <div class="session-header">
                                            <h3 class="session-title">{{ $session['title'] }}</h3>
                                            <div class="time-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" width="16" height="16">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $session['time_range'] }}
                                            </div>
                                        </div>

                                        <div class="meta-row">
                                            {{-- Meta items sama seperti di atas --}}
                                            <div class="meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                {{ $session['package'] }}
                                            </div>
                                            <div class="meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                {{ $session['subject'] }}
                                            </div>
                                            <div class="meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $session['location'] }}
                                            </div>
                                            <div class="meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                {{ $session['participant_summary'] }}
                                            </div>
                                        </div>

                                        @if(str_contains($session['location'], 'Online') && !empty($session['zoom_link']))
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $hasStarted = $session['start_at'] && $session['start_at']->lte($now);
                                            @endphp
                                            <div style="margin-top: 12px;">
                                                @if($hasStarted)
                                                    <a href="{{ $session['zoom_link'] }}" target="_blank" rel="noopener" class="zoom-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M15.5 5H19a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3.5m-11-4v-8a2 2 0 0 1 2-2H11a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6.5a2 2 0 0 1-2-2z">
                                                            </path>
                                                            <polyline points="15 10 20 7 20 17 15 14"></polyline>
                                                        </svg>
                                                        Join Zoom
                                                    </a>
                                                @else
                                                    <button disabled class="zoom-btn"
                                                        style="background: #cbd5e1; color: #64748b; cursor: not-allowed; opacity: 0.6;"
                                                        title="Sesi belum dimulai">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <polyline points="12 6 12 12 16 14"></polyline>
                                                        </svg>
                                                        Dimulai {{ $session['start_at']->locale('id')->diffForHumans() }}
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($futureSessions->count() > 10)
                            <div style="text-align: center; margin-top: 24px;">
                                <button id="toggle-future-btn" onclick="toggleFutureSessions()"
                                    style="background: var(--primary-solid); color: white; border: none; padding: 12px 32px; border-radius: 99px; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: all 0.2s;">
                                    Tampilkan Lebih Banyak ({{ $futureSessions->count() - 10 }} sesi lagi)
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- 3. KOLOM RIWAYAT (KANAN) --}}
            <div class="history-sidebar">
                <div class="section-header" style="margin-bottom: 24px;">
                    <h2 class="section-title" style="font-size: 1.1rem;">Riwayat Terakhir</h2>
                </div>

                @if ($historySessions->isEmpty())
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Belum ada riwayat sesi.</p>
                @else
                    <div class="history-list">
                        @foreach ($historySessions->take(5) as $session)
                            @php $date = $session['start_at']; @endphp
                            <div class="history-item">
                                <div class="history-date">
                                    {{ $date ? $date->format('d M') : '-' }}
                                </div>
                                <div class="history-content">
                                    <h4>{{ $session['title'] }}</h4>
                                    <span
                                        class="history-status status-{{ $session['status_variant'] === 'success' ? 'completed' : 'cancelled' }}">
                                        {{ $session['status_label'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        let showingAllFuture = false;
        function toggleFutureSessions() {
            const sessions = document.querySelectorAll('[data-session-index]');
            const btn = document.getElementById('toggle-future-btn');

            showingAllFuture = !showingAllFuture;

            sessions.forEach((session, index) => {
                const sessionIndex = parseInt(session.getAttribute('data-session-index'));
                if (sessionIndex >= 10) {
                    session.style.display = showingAllFuture ? 'grid' : 'none';
                }
            });

            if (showingAllFuture) {
                btn.textContent = 'Tampilkan Lebih Sedikit';
            } else {
                const totalSessions = sessions.length;
                const hiddenCount = totalSessions - 10;
                btn.textContent = `Tampilkan Lebih Banyak (${hiddenCount} sesi lagi)`;
            }
        }
    </script>
    <script>
        function toggleFutureSessions() {
            const sessions = document.querySelectorAll('#future-sessions-list .session-card');
            const btn = document.getElementById('toggle-future-btn');
            let isShowingAll = btn.textContent.includes('Lebih Sedikit');

            sessions.forEach((el, index) => {
                if (index >= 10) {
                    el.style.display = isShowingAll ? 'none' : 'grid'; // grid because that's the display type
                }
            });

            if (isShowingAll) {
                const hiddenCount = sessions.length - 10;
                btn.textContent = `Tampilkan Lebih Banyak (${hiddenCount} sesi lagi)`;
            } else {
                btn.textContent = 'Tampilkan Lebih Sedikit';
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // You might want to use a toast here later, for now just a simple alert or console
                // alert('Link Zoom berhasil disalin!'); // Removed as requested
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>
@endsection