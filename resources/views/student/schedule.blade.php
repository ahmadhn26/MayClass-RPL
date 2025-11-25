@extends('student.layouts.app')

@section('title', 'Jadwal Belajar')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --primary-hover: #115e59;
            --primary-light: #ccfbf1;
            --surface: #ffffff;
            --bg-body: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-sm: 0 1px 3px 0 rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        /* --- Layout Container --- */
        .schedule-container {
            width: 100%;
            padding: 0 40px;
            display: flex;
            flex-direction: column;
            gap: 48px;
            max-width: 1600px; /* Batas lebar agar tidak terlalu stretch di layar ultrawide */
            margin: 0 auto;
        }

        /* --- 1. Hero Section --- */
        .hero-banner {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            border-radius: var(--radius-lg);
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%; right: -10%;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content { position: relative; z-index: 2; max-width: 800px; }
        
        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 12px;
            line-height: 1.2;
        }
        
        .hero-desc {
            font-size: 1.05rem;
            opacity: 0.95;
            margin: 0 0 24px;
            line-height: 1.6;
        }

        .hero-stats {
            display: inline-flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-hero {
            background: white;
            color: var(--primary);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid white;
        }
        .btn-hero:hover { background: transparent; color: white; }
        
        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 1px solid rgba(255,255,255,0.6);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.1); border-color: white; }

        /* --- Section Title --- */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 1px solid var(--border);
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .section-title h2 { font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0 0 4px; }
        .section-title p { color: var(--text-muted); margin: 0; font-size: 0.95rem; }
        
        /* --- Highlight Card (Nearest Session) --- */
        .highlight-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 32px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            overflow: hidden;
            border-left: 6px solid var(--primary); /* Aksen warna di kiri */
        }

        .highlight-label {
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.05em;
        }

        .highlight-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .highlight-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            color: var(--text-muted);
            font-size: 1rem;
            align-items: center;
        }
        .highlight-meta span { display: inline-flex; align-items: center; gap: 6px; }

        /* --- Upcoming Grid --- */
        .upcoming-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        .session-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .session-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: #cbd5e1;
        }

        .session-cat {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary);
            background: var(--primary-light);
            padding: 4px 10px;
            border-radius: 6px;
            width: fit-content;
            margin-bottom: 12px;
        }

        .session-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 8px;
            line-height: 1.4;
        }

        .session-time {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* --- Calendar Controls --- */
        .calendar-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
            background: var(--surface);
            padding: 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            margin-bottom: 24px;
        }

        /* Segmented Control for Tabs */
        .tabs-group {
            display: flex;
            background: var(--bg-body);
            padding: 4px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .tab-link {
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
        }

        .tab-link.is-active {
            background: white;
            color: var(--text-main);
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .nav-group {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .nav-btn {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background 0.2s;
        }
        .nav-btn:hover { background: var(--bg-body); }

        .current-date {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-main);
        }

        /* --- Calendar Grid --- */
        .calendar-grid {
            display: grid;
            gap: 1px;
            background: var(--border); /* Creates grid lines */
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .calendar-cell {
            background: var(--surface);
            padding: 16px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: background 0.2s;
        }

        .calendar-cell.is-active {
            background: #f0fdfa; /* Light Teal background for active day */
        }

        .calendar-cell:hover {
            background: #fafafa;
        }

        .cell-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .day-number {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .day-name {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* Event Item inside Calendar */
        .calendar-event {
            background: var(--primary-light);
            border-left: 3px solid var(--primary);
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            color: var(--text-main);
        }
        
        .event-time {
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--primary);
            display: block;
            margin-bottom: 2px;
        }

        /* --- Range List --- */
        .range-list {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .range-day {
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface);
            overflow: hidden;
        }

        .range-header {
            background: var(--bg-body);
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
            font-weight: 700;
            color: var(--text-main);
        }

        .range-session {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .range-session:last-child { border-bottom: none; }

        .rs-title { font-weight: 600; color: var(--text-main); }
        .rs-meta { font-size: 0.9rem; color: var(--text-muted); }

        /* Empty State */
        .empty-box {
            text-align: center;
            padding: 40px;
            background: var(--bg-body);
            border: 1px dashed var(--border);
            border-radius: var(--radius-md);
            color: var(--text-muted);
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
        }
        .btn-primary:hover { background: var(--primary-hover); }

        @media (max-width: 768px) {
            .schedule-container { padding: 0 20px; }
            .calendar-controls { flex-direction: column; align-items: stretch; }
            .nav-group { justify-content: space-between; }
            .calendar-cell { min-height: auto; }
        }
    </style>
@endpush

@php($calendar = $schedule['calendar'])
@php($viewMode = $schedule['view'])
@php($rangeSessions = $schedule['rangeSessions'])

@section('content')
    <div class="schedule-container">

        {{-- 1. Hero Section --}}
        <div class="hero-banner">
            <div class="hero-content">
                <h1 class="hero-title">Jadwal Bimbel</h1>
                <p class="hero-desc">
                    @if ($enrolledPackages->isNotEmpty())
                        @if ($enrolledPackages->count() === 1)
                            Agenda eksklusif untuk paket <strong>{{ optional($enrolledPackages->first())->detail_title ?? optional($enrolledPackages->first())->title }}</strong>.
                        @else
                            Agenda eksklusif untuk paket yang Anda ikuti: <strong>{{ $enrolledPackages->map(fn ($package) => $package->detail_title ?? $package->title)->join(', ') }}</strong>.
                        @endif
                    @endif
                    Total <strong>{{ number_format($stats['total']) }}</strong> sesi tercatat, dengan
                    <strong>{{ number_format($stats['upcoming']) }}</strong> agenda mendatang.
                </p>
                
                <div class="hero-stats">
                    <span class="hero-badge">{{ $calendar['label'] }}</span>
                    <span class="hero-badge">Hari Aktif: {{ number_format(count($rangeSessions)) }}</span>
                </div>

                <div class="hero-actions">
                    <a class="btn-hero" href="#calendar-section">Lihat Kalender</a>
                    <a class="btn-hero-outline" href="{{ route('student.dashboard') }}">Kembali ke Beranda</a>
                </div>
            </div>
        </div>

        {{-- 2. Sesi Terdekat --}}
        <section>
            <div class="highlight-card">
                <span class="highlight-label">Sesi Berikutnya</span>
                <h2 class="highlight-title">{{ $schedule['highlight']['title'] }}</h2>
                <div class="highlight-meta">
                    <span> {{ $schedule['highlight']['date'] }}</span>
                    <span> {{ $schedule['highlight']['time'] }}</span>
                    <span> {{ $schedule['highlight']['mentor'] }}</span>
                    <span> {{ $schedule['highlight']['category'] }}</span>
                </div>
                <div style="margin-top: 8px;">
                    <a href="{{ route('student.dashboard') }}" class="btn-primary">
                        Buka Dashboard
                    </a>
                </div>
            </div>
        </section>

        {{-- 3. Agenda Mendatang --}}
        <section>
            <div class="section-header">
                <div class="section-title">
                    <h2>Agenda Mendatang</h2>
                </div>
            </div>

            @if (! empty($schedule['upcoming']) && count($schedule['upcoming']) > 0)
                <div class="upcoming-grid">
                    @foreach ($schedule['upcoming'] as $session)
                        <article class="session-card">
                            <div>
                                <div class="session-cat">{{ $session['category'] }}</div>
                                <h3 class="session-title">{{ $session['title'] }}</h3>
                            </div>
                            <div class="session-time">
                                {{ $session['date'] }} • {{ $session['time'] }}
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-box">
                    Belum ada jadwal sesi mendatang.
                </div>
            @endif
        </section>

        {{-- 4. Kalender Sesi --}}
        <section id="calendar-section">
            <div class="section-header">
                <div class="section-title">
                    <h2>Kalender Sesi</h2>
                    <p>Lihat jadwal lengkap dalam tampilan kalender.</p>
                </div>
            </div>

            {{-- Calendar Controls --}}
            <div class="calendar-controls">
                <div class="tabs-group">
                    @foreach (['day' => 'Harian', 'week' => 'Mingguan', 'month' => 'Bulanan'] as $mode => $label)
                        <a 
                            class="tab-link {{ $viewMode === $mode ? 'is-active' : '' }}"
                            href="{{ route('student.schedule', ['view' => $mode, 'date' => $calendar['currentDate']]) }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <div class="nav-group">
                    <a href="{{ route('student.schedule', ['view' => $viewMode, 'date' => $calendar['prevDate']]) }}" class="nav-btn">
                        Sebelumnya
                    </a>
                    <span class="current-date">{{ $calendar['label'] }}</span>
                    <a href="{{ route('student.schedule', ['view' => $viewMode, 'date' => $calendar['nextDate']]) }}" class="nav-btn">
                        Berikutnya &rarr;
                    </a>
                </div>
            </div>

            {{-- Calendar Grid --}}
            <div class="calendar-grid" style="grid-template-columns: repeat({{ $calendar['columns'] }}, minmax(0, 1fr));">
                @foreach ($calendar['weeks'] as $week)
                    @foreach ($week as $day)
                        <div class="calendar-cell {{ $day['isActive'] ? 'is-active' : '' }}">
                            <div class="cell-header">
                                <span class="day-number">{{ $day['display'] }}</span>
                                <span class="day-name">
                                    {{ $viewMode === 'day' ? $day['fullLabel'] : $day['weekday'] }}
                                </span>
                            </div>

                            @if (! empty($day['sessions']))
                                @foreach ($day['sessions'] as $event)
                                    <div class="calendar-event">
                                        <span class="event-time">{{ $event['start_time'] ?? '-' }} WIB</span>
                                        <span>{{ $event['title'] }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endforeach
            </div>
        </section>

        {{-- 5. Rincian Range List --}}
        <section>
            <div class="section-header">
                <div class="section-title">
                    <h2>Rincian Jadwal</h2>
                    <p>Detail sesi pada periode ini.</p>
                </div>
            </div>

            @if (! empty($rangeSessions))
                <div class="range-list">
                    @foreach ($rangeSessions as $day)
                        <div class="range-day">
                            <div class="range-header">{{ $day['label'] }}</div>
                            <div>
                                @foreach ($day['sessions'] as $session)
                                    <div class="range-session">
                                        <span class="rs-title">{{ $session['title'] }}</span>
                                        <div class="rs-meta">
                                            {{ $session['time'] }} • Mentor {{ $session['mentor'] }} • {{ $session['category'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-box">
                    Tidak ada sesi pada rentang tanggal ini.
                </div>
            @endif
        </section>

    </div>
@endsection