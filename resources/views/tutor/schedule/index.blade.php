@extends('tutor.layout')

@section('title', 'Jadwal Mengajar - MayClass')

@push('styles')
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #eef2ff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-card: #ffffff;
            --bg-page: #f8fafc;
        }

        .page-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            gap: 32px;
        }

        /* Hero Section */
        .hero-card {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border-radius: 24px;
            padding: 32px;
            color: white;
            display: grid;
            gap: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .hero-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            flex-wrap: wrap;
        }

        .hero-content h1 {
            margin: 0 0 8px 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .hero-content p {
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            max-width: 600px;
            line-height: 1.5;
        }

        .metrics-row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .metric-pill {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 20px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            min-width: 140px;
        }

        .metric-pill span {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .metric-pill strong {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Schedule Layout */
        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 32px;
            align-items: start;
        }

        .main-schedule {
            display: grid;
            gap: 24px;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-title .count-badge {
            background: var(--primary-light);
            color: var(--primary-color);
            font-size: 0.875rem;
            padding: 2px 10px;
            border-radius: 999px;
        }

        /* Session Card */
        .session-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 24px;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .session-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        .date-box {
            background: var(--primary-light);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 90px;
            text-align: center;
            border: 1px solid rgba(79, 70, 229, 0.1);
        }

        .date-box .day {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .date-box .date {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
            margin: 4px 0;
        }

        .date-box .month {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
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
            font-size: 1.125rem;
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
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.875rem;
            color: var(--text-muted);
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .meta-item svg {
            width: 16px;
            height: 16px;
            color: #94a3b8;
        }

        /* History Section (Sidebar) */
        .history-sidebar {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 24px;
            height: fit-content;
        }

        .history-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .history-item {
            display: flex;
            gap: 12px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .history-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .history-date {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            min-width: 60px;
        }

        .history-content h4 {
            margin: 0 0 4px 0;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .history-status {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .status-completed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            background: #f8fafc;
            border-radius: 16px;
            border: 2px dashed var(--border-color);
            color: var(--text-muted);
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
            }
            
            .date-box .date {
                font-size: 1.25rem;
                margin: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="hero-card">
            <div class="hero-header">
                <div class="hero-content">
                    <h1>Agenda Mengajar</h1>
                    <p>Kelola dan pantau jadwal sesi kelas Anda. Pastikan hadir tepat waktu untuk memberikan pengalaman belajar terbaik.</p>
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
        </div>

        <div class="schedule-grid">
            <!-- Main Column: Upcoming Sessions -->
            <div class="main-schedule">
                <!-- Today's Schedule -->
                <div class="section-title">
                    Jadwal Hari Ini
                    <span class="count-badge">{{ $todaySessions->count() }}</span>
                </div>

                @if ($todaySessions->isEmpty())
                    <div class="empty-state" style="margin-bottom: 32px;">
                        <p>Tidak ada jadwal mengajar hari ini.</p>
                    </div>
                @else
                    <div class="session-list" style="margin-bottom: 32px;">
                        @foreach ($todaySessions as $session)
                            @php
                                $date = $session['start_at'];
                            @endphp
                            <div class="session-card" style="border-color: var(--primary-color); background: #f0fdf4;">
                                <div class="date-box" style="background: #dcfce7; color: #166534; border-color: #bbf7d0;">
                                    <span class="day" style="color: #166534;">HARI INI</span>
                                    <span class="date" style="color: #14532d;">{{ $date ? $date->format('d') : '-' }}</span>
                                    <span class="month" style="color: #166534;">{{ $date ? $date->locale('id')->isoFormat('MMM') : '-' }}</span>
                                </div>
                                
                                <div class="session-details">
                                    <div class="session-header">
                                        <h3 class="session-title">{{ $session['title'] }}</h3>
                                        <div class="time-badge" style="background: #166534; color: white;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $session['time_range'] }}
                                        </div>
                                    </div>

                                    <div class="meta-row">
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            {{ $session['package'] }}
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            {{ $session['subject'] }}
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $session['location'] }}
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            {{ $session['participant_summary'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Future Schedule -->
                <div class="section-title">
                    Jadwal Mendatang
                    <span class="count-badge">{{ $futureSessions->count() }}</span>
                </div>

                @if ($futureSessions->isEmpty())
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" style="margin-bottom: 16px; color: #cbd5e1;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Belum ada jadwal mendatang lainnya.</p>
                    </div>
                @else
                    <div class="session-list" id="future-sessions-list">
                        @foreach ($futureSessions as $index => $session)
                            @php
                                $date = $session['start_at'];
                            @endphp
                            <div class="session-card" data-session-index="{{ $index }}" style="{{ $index >= 10 ? 'display: none;' : '' }}">
                                <div class="date-box">
                                    <span class="day">{{ $date ? $date->locale('id')->isoFormat('ddd') : '-' }}</span>
                                    <span class="date">{{ $date ? $date->format('d') : '-' }}</span>
                                    <span class="month">{{ $date ? $date->locale('id')->isoFormat('MMM Y') : '-' }}</span>
                                </div>
                                
                                <div class="session-details">
                                    <div class="session-header">
                                        <h3 class="session-title">{{ $session['title'] }}</h3>
                                        <div class="time-badge">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $session['time_range'] }}
                                        </div>
                                    </div>

                                    <div class="meta-row">
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            {{ $session['package'] }}
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            {{ $session['subject'] }}
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $session['location'] }}
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            {{ $session['participant_summary'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if ($futureSessions->count() > 10)
                        <div style="text-align: center; margin-top: 24px;">
                            <button id="toggle-future-btn" onclick="toggleFutureSessions()" style="background: var(--primary-color); color: white; border: none; padding: 12px 32px; border-radius: 12px; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: all 0.2s;">
                                Tampilkan Lebih Banyak ({{ $futureSessions->count() - 10 }} sesi lagi)
                            </button>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Sidebar: History -->
            <div class="history-sidebar">
                <div class="section-title">
                    Riwayat Terakhir
                </div>

                @if ($historySessions->isEmpty())
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Belum ada riwayat sesi.</p>
                @else
                    <div class="history-list">
                        @foreach ($historySessions->take(5) as $session)
                            @php
                                $date = $session['start_at'];
                            @endphp
                            <div class="history-item">
                                <div class="history-date">
                                    {{ $date ? $date->format('d M') : '-' }}
                                </div>
                                <div class="history-content">
                                    <h4>{{ $session['title'] }}</h4>
                                    <span class="history-status status-{{ $session['status_variant'] === 'success' ? 'completed' : 'cancelled' }}">
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
    // Toggle show more/less future sessions
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
@endsection
