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
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* --- Animations --- */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes gentle-pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            }

            50% {
                transform: scale(1.02);
                box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
            }
        }


        /* --- Layout Container --- */
        .schedule-container {
            width: 100%;
            padding: 0 40px;
            display: flex;
            flex-direction: column;
            gap: 40px;
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
            max-width: 800px;
        }

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

        .btn-hero:hover {
            background: transparent;
            color: white;
        }

        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.6);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        /* --- Section Title --- */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 1px solid var(--border);
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .section-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 4px;
        }

        .section-title p {
            color: var(--text-muted);
            margin: 0;
            font-size: 0.95rem;
        }

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
            border-left: 6px solid var(--primary);
            /* Aksen warna di kiri */
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

        .highlight-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

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
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
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

        .nav-btn:hover {
            background: var(--bg-body);
        }

        .current-date {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-main);
        }

        /* --- Calendar Grid --- */
        .calendar-grid {
            display: grid;
            gap: 12px;
            background: transparent;
            border: none;
            border-radius: var(--radius-md);
            overflow: visible;
            padding: 4px;
        }

        .calendar-cell {
            background: var(--surface);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            min-height: 110px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .calendar-cell.is-active {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-color: #7dd3fc;
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.15);
        }

        .calendar-cell:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .cell-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .day-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
        }

        .day-name {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* Event Item inside Calendar */
        .calendar-event {
            background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
            border-left: 3px solid #3b82f6;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--text-main);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .calendar-event:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
        }

        .event-time {
            font-weight: 700;
            font-size: 0.75rem;
            color: #2563eb;
            display: block;
            margin-bottom: 4px;
            letter-spacing: 0.025em;
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

        .range-session:last-child {
            border-bottom: none;
        }

        .rs-title {
            font-weight: 600;
            color: var(--text-main);
        }

        .rs-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

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

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        /* --- Responsive Mobile Styles --- */
        @media (max-width: 768px) {
            .schedule-container {
                padding: 0 16px;
                gap: 32px;
            }

            /* Hero adjustments */
            .hero-banner {
                padding: 24px;
            }

            .hero-title {
                font-size: 1.5rem;
            }

            .hero-desc {
                font-size: 0.95rem;
            }

            .hero-actions {
                flex-direction: column;
            }

            .btn-hero,
            .btn-hero-outline {
                width: 100%;
                text-align: center;
                justify-content: center;
            }

            /* Highlight card */
            .highlight-card {
                padding: 20px;
            }

            .highlight-title {
                font-size: 1.3rem;
            }

            /* Calendar controls - stack vertically */
            .calendar-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                padding: 12px;
            }

            /* Tabs - full width on mobile */
            .tabs-group {
                width: 100%;
                justify-content: space-between;
            }

            .tab-link {
                flex: 1;
                text-align: center;
                padding: 10px 12px;
                font-size: 0.85rem;
            }

            /* Navigation group */
            .nav-group {
                justify-content: space-between;
                width: 100%;
            }

            .nav-btn {
                font-size: 0.85rem;
                padding: 8px;
            }

            .current-date {
                font-size: 0.9rem;
            }

            /* Calendar wrapper - contains the scroll */
            .calendar-wrapper {
                width: 100%;
                overflow-x: auto;
                overflow-y: visible;
                -webkit-overflow-scrolling: touch;
                margin-bottom: 16px;
            }

            /* Custom scrollbar for wrapper */
            .calendar-wrapper::-webkit-scrollbar {
                height: 10px;
            }

            .calendar-wrapper::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }

            .calendar-wrapper::-webkit-scrollbar-thumb {
                background: var(--primary);
                border-radius: 4px;
            }

            .calendar-wrapper::-webkit-scrollbar-thumb:hover {
                background: var(--primary-hover);
            }

            /* Calendar grid - no overflow, let wrapper handle it */
            .calendar-grid {
                overflow: visible !important;
                display: grid !important;
            }

            /* For month/week view (7 columns), set minimum width */
            .calendar-grid[style*="repeat(7"] {
                min-width: 700px;
            }

            /* Ensure cells maintain size */
            .calendar-grid[style*="repeat(7"] .calendar-cell {
                min-width: 100px;
                min-height: 100px;
                padding: 12px;
            }

            /* For day view (1 column), full width */
            .calendar-grid[style*="repeat(1"] {
                min-width: auto;
                width: 100%;
            }

            .calendar-cell {
                min-height: 80px;
                padding: 12px;
            }

            .day-number {
                font-size: 1rem;
            }

            .day-name {
                font-size: 0.7rem;
            }

            .calendar-event {
                font-size: 0.75rem;
                padding: 4px 8px;
            }

            .event-time {
                font-size: 0.7rem;
            }

            /* Upcoming grid - single column on mobile */
            .upcoming-grid {
                grid-template-columns: 1fr;
            }

            /* Range list adjustments */
            .range-session {
                padding: 12px 16px;
            }

            .rs-title {
                font-size: 0.95rem;
            }

            .rs-meta {
                font-size: 0.8rem;
            }
        }

        /* Extra small devices */
        @media (max-width: 480px) {
            .schedule-container {
                padding: 0 12px;
            }

            .hero-banner {
                padding: 20px;
            }

            .hero-title {
                font-size: 1.3rem;
            }

            .hero-stats {
                flex-direction: column;
                align-items: flex-start;
            }

            .highlight-card {
                padding: 16px;
            }

            .highlight-title {
                font-size: 1.1rem;
            }

            .highlight-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .tab-link {
                padding: 8px 8px;
                font-size: 0.8rem;
            }

            .calendar-cell {
                min-height: 70px;
                padding: 8px;
            }

            .day-number {
                font-size: 0.9rem;
            }
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
                            Agenda eksklusif untuk paket
                            <strong>{{ optional($enrolledPackages->first())->detail_title ?? optional($enrolledPackages->first())->title }}</strong>.
                        @else
                            Agenda eksklusif untuk paket yang Anda ikuti:
                            <strong>{{ $enrolledPackages->map(fn($package) => $package->detail_title ?? $package->title)->join(', ') }}</strong>.
                        @endif
                    @endif
                    Total <strong>{{ number_format($stats['total']) }}</strong> sesi tercatat, dengan
                    <strong>{{ number_format($stats['upcoming']) }}</strong> agenda mendatang.
                </p>

                <div class="hero-stats">
                    <span class="hero-badge">{{ $calendar['label'] }}</span>
                    <span class="hero-badge">Hari Aktif: {{ number_format(count($rangeSessions)) }}</span>
                </div>
            </div>
        </div>


        {{-- 2. Sesi Sedang Berjalan (Live Session) --}}
    @if (!empty($schedule['current']))
        <section>
            <div class="highlight-card"
                style="border-left-color: #ef4444; background: linear-gradient(135deg, #fef2f2 0%, #fff 100%);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <span class="highlight-label"
                        style="color: #ef4444; background: #fee2e2; padding: 6px 12px; border-radius: 99px; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 6px;">
                        <span
                            style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></span>
                        SEDANG BERLANGSUNG
                    </span>
                </div>
                <h2 class="highlight-title" style="color: #dc2626;">{{ $schedule['current']['title'] }}</h2>
                <div class="highlight-meta">
                    <span>{{ $schedule['current']['date'] }}</span>
                    <span>{{ $schedule['current']['time'] }}</span>
                    <span>Mentor: {{ $schedule['current']['mentor'] }}</span>
                    <span>{{ $schedule['current']['category'] }}</span>
                </div>
                @if (!empty($schedule['current']['zoom_link']))
                    <div style="margin-top: 16px;">
                        <a href="{{ $schedule['current']['zoom_link'] }}" target="_blank" class="btn-primary"
                            style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); animation: gentle-pulse 2s ease-in-out infinite;">
                            Join Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- 3. Sesi Berikutnya --}}
    <section>
        <div class="highlight-card">
            <span class="highlight-label">Sesi Berikutnya</span>
            <h2 class="highlight-title">{{ $schedule['highlight']['title'] }}</h2>
            <div class="highlight-meta">
                <span>{{ $schedule['highlight']['date'] }}</span>
                <span>{{ $schedule['highlight']['time'] }}</span>
                <span>Mentor: {{ $schedule['highlight']['mentor'] }}</span>
                <span>{{ $schedule['highlight']['category'] }}</span>
            </div>
            @if (!empty($schedule['highlight']['zoom_link']))
                <div style="margin-top: 16px; display: flex; gap: 10px; align-items: center;">
                    <a href="{{ $schedule['highlight']['zoom_link'] }}" 
                       target="_blank" 
                       class="btn-primary zoom-button time-restricted"
                       data-start-time="{{ $schedule['highlight']['start_at_iso'] ?? '' }}"
                       data-duration="{{ $schedule['highlight']['duration_minutes'] ?? 90 }}"
                       style="background: #2d8cff; display: inline-flex; align-items: center; gap: 8px; width: fit-content; padding: 10px 24px; justify-content: center;">
                        <span class="button-text">Join Zoom Meeting</span>
                        <span class="countdown-text" style="display: none; font-size: 0.85rem; opacity: 0.9;"></span>
                    </a>
                    <button type="button" class="btn-icon copy-link-btn" 
                        onclick="copyToClipboard('{{ $schedule['highlight']['zoom_link'] }}')"
                        style="padding: 10px; border-radius: 8px; border: 1px solid var(--border); background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;"
                        title="Salin Link Zoom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                    </button>
                </div>
            @endif
        </div>
    </section>

    {{-- 3. Agenda Mendatang --}}
    <section>
        <div class="section-header">
            <div class="section-title">
                <h2>Agenda Mendatang</h2>
            </div>
        </div>

        @if (!empty($schedule['upcoming']) && count($schedule['upcoming']) > 0)
            <div class="upcoming-grid">
                @foreach ($schedule['upcoming'] as $session)
                    <article class="session-card">
                        <div>
                            <div class="session-cat">{{ $session['category'] }}</div>
                            <h3 class="session-title">{{ $session['title'] }}</h3>
                            @if (!empty($session['zoom_link']))
                                <div style="display: flex; gap: 8px; margin-top: 12px; align-items: stretch;">
                                    <a href="{{ $session['zoom_link'] }}" 
                                       target="_blank"
                                       class="zoom-button time-restricted"
                                       data-start-time="{{ $session['start_at_iso'] ?? '' }}"
                                       data-duration="{{ $session['duration_minutes'] ?? 90 }}"
                                       style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 8px 16px; background: #2d8cff; color: white; text-decoration: none; border-radius: 8px; font-size: 0.9rem; font-weight: 600; transition: all 0.2s;"
                                       onmouseover="if(!this.classList.contains('disabled')) this.style.background='#1a73e8'" 
                                       onmouseout="if(!this.classList.contains('disabled')) this.style.background='#2d8cff'">
                                        <span class="button-text">Join Online</span>
                                        <span class="countdown-text" style="display: none; font-size: 0.8rem;"></span>
                                    </a>
                                    <button type="button" class="btn-icon copy-link-btn" 
                                        onclick="copyToClipboard('{{ $session['zoom_link'] }}')"
                                        style="padding: 0 12px; border-radius: 8px; border: 1px solid var(--border); background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;"
                                        title="Salin Link Zoom">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                    </button>
                                </div>
                            @endif

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
                    <a class="tab-link {{ $viewMode === $mode ? 'is-active' : '' }}"
                        href="{{ route('student.schedule', ['view' => $mode, 'date' => $calendar['currentDate']]) }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="nav-group">
                <a href="{{ route('student.schedule', ['view' => $viewMode, 'date' => $calendar['prevDate']]) }}"
                    class="nav-btn">
                    Sebelumnya
                </a>
                <span class="current-date">{{ $calendar['label'] }}</span>
                <a href="{{ route('student.schedule', ['view' => $viewMode, 'date' => $calendar['nextDate']]) }}"
                    class="nav-btn">
                    Berikutnya &rarr;
                </a>
            </div>
        </div>

        {{-- Calendar Grid Wrapper for Mobile Scroll --}}
        <div class="calendar-wrapper">
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

                            @if (!empty($day['sessions']))
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
        </div>
    </section>

    {{-- 5. Riwayat Pertemuan --}}
    <section>
        <div class="section-header">
            <div class="section-title">
                <h2>Riwayat Pertemuan</h2>
                <p>Daftar sesi yang sudah selesai.</p>
            </div>
        </div>

        @if (!empty($rangeSessions) && count($rangeSessions) > 0)
            <div class="range-list">
                @foreach ($rangeSessions as $session)
                    <div class="range-session"
                        style="border: 1px solid var(--border); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 12px; background: var(--surface);">
                        <div style="display: flex; justify-content: space-between; align-items: start; gap: 12px;">
                            <div style="flex: 1;">
                                <span class="rs-title">{{ $session['title'] }}</span>
                                <div class="rs-meta">
                                    {{ $session['date'] }} • {{ $session['time'] }} • Mentor {{ $session['mentor'] }} •
                                    {{ $session['category'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-box">
                Belum ada riwayat pertemuan.
            </div>
        @endif
    </section>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateZoomButtons() {
                const buttons = document.querySelectorAll('.zoom-button.time-restricted');
                const now = new Date();

                buttons.forEach(btn => {
                    const startIso = btn.getAttribute('data-start-time');
                    const textSpan = btn.querySelector('.button-text');
                    const countdownSpan = btn.querySelector('.countdown-text');
                    const copyBtn = btn.nextElementSibling; // The copy button next to it

                    if (!startIso) return;

                    const startTime = new Date(startIso);
                    const timeDiff = startTime - now;
                    const hoursDiff = timeDiff / (1000 * 60 * 60);

                    // Logic: Enable if within 5 hours before start (H-5)
                    // Or if session already started (timeDiff < 0)
                    if (hoursDiff <= 5) {
                        // ACTIVE
                        btn.classList.remove('disabled');
                        btn.style.opacity = '1';
                        btn.style.pointerEvents = 'auto';
                        btn.style.filter = 'none';
                        if (copyBtn) {
                            copyBtn.disabled = false;
                            copyBtn.style.opacity = '1';
                            copyBtn.style.cursor = 'pointer';
                        }
                        
                        if (textSpan) textSpan.style.display = 'inline';
                        if (countdownSpan) countdownSpan.style.display = 'none';
                    } else {
                        // DISABLED (More than 5 hours to go)
                        btn.classList.add('disabled');
                        btn.style.opacity = '0.6';
                        btn.style.pointerEvents = 'none';
                        btn.style.filter = 'grayscale(100%)';
                        
                        // Disable copy button too based on requirements ("ikut aturan H-5")
                        if (copyBtn) {
                            copyBtn.disabled = true;
                            copyBtn.style.opacity = '0.5';
                            copyBtn.style.cursor = 'not-allowed';
                        }

                        if (textSpan) textSpan.style.display = 'none';
                        if (countdownSpan) {
                            countdownSpan.style.display = 'inline';
                            const hours = Math.floor(hoursDiff);
                            countdownSpan.textContent = `Zoom Aktif ${hours} jam lagi`;
                        }
                    }
                });
            }

            // Run immediately and every minute
            updateZoomButtons();
            setInterval(updateZoomButtons, 60000);
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Visual feedback - popup removed as requested
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endpush