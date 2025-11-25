@extends('tutor.layout')

@section('title', 'Dashboard Tentor - MayClass')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        --radius-lg: 24px;
        --radius-md: 16px;
    }

    .dashboard-content {
        display: flex;
        flex-direction: column;
        gap: 32px;
        max-width: 1280px;
        margin: 0 auto;
    }

    /* --- Hero Section --- */
    .hero-panel {
        position: relative;
        display: grid;
        grid-template-columns: 1fr 300px;
        align-items: center;
        gap: 48px;
        padding: 48px;
        border-radius: var(--radius-lg);
        background: var(--primary-gradient);
        color: #fff;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Dekorasi background */
    .hero-panel::before, .hero-panel::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        z-index: 1;
    }
    .hero-panel::before { width: 300px; height: 300px; top: -100px; right: -50px; }
    .hero-panel::after { width: 200px; height: 200px; bottom: -50px; left: 10%; }

    .hero-copy {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .hero-panel h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .hero-panel p {
        margin: 0;
        font-size: 1.1rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        max-width: 600px;
    }

    .hero-visual {
        position: relative;
        z-index: 2;
        border-radius: var(--radius-md);
        margin: 0;
        height: 220px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transform: rotate(2deg);
        transition: transform 0.3s ease;
    }

    .hero-visual:hover {
        transform: rotate(0deg) scale(1.02);
    }

    .hero-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
        display: block;
    }

    /* --- Stats Grid --- */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }

    .stat-card {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 12px;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
        border-color: #cbd5e1;
    }

    /* Aksen Warna Sidebar Kecil */
    .stat-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: currentColor;
    }

    .stat-card.accent-blue { color: #2563eb; }
    .stat-card.accent-mint { color: #10b981; }
    .stat-card.accent-orange { color: #f97316; }
    .stat-card.accent-purple { color: #8b5cf6; }

    .stat-card .label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-card .value-row {
        display: flex;
        align-items: baseline;
        gap: 6px;
    }

    .stat-card .value-row strong {
        font-size: 2.25rem;
        color: #0f172a;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.03em;
    }

    .stat-card .value-row .suffix {
        font-size: 0.875rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
    }

    .stat-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.925rem;
        line-height: 1.5;
    }

    /* --- Schedule Section --- */
    .schedule-card {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 32px;
        border: 1px solid #e2e8f0;
        box-shadow: var(--card-shadow);
    }

    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .schedule-header h2 {
        margin: 0;
        font-size: 1.5rem;
        color: #0f172a;
        font-weight: 700;
    }

    .schedule-header span {
        margin-top: 8px;
        display: block;
        color: #64748b;
        font-size: 0.95rem;
    }

    .timeline {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .timeline-item {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 24px;
        padding: 20px;
        border-radius: 16px;
        transition: background 0.2s, transform 0.2s;
        border-left: 4px solid transparent; /* Invisible border default */
    }

    .timeline-item:hover {
        background: #f8fafc;
    }

    /* Highlight Logic Styling */
    .timeline-item.is-highlight {
        background: #eff6ff; /* Light Blue Background */
        border-left-color: #2563eb; /* Blue accent line */
    }

    .timeline-item .time {
        font-family: 'Monaco', 'Consolas', monospace; /* Monospace for cleaner numbers */
        font-weight: 600;
        color: #334155;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    .timeline-item .details {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .timeline-item .details strong {
        font-size: 1.125rem;
        color: #0f172a;
        font-weight: 600;
    }

    .timeline-item .details span {
        color: #64748b;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Dot separator for span details */
    .timeline-item .details span::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 4px;
        background: #cbd5e1;
        border-radius: 50%;
    }
    .timeline-item .details span:first-of-type::before { display: none; }

    .empty-timeline {
        text-align: center;
        padding: 64px 24px;
        border-radius: var(--radius-md);
        background: #f8fafc;
        border: 2px dashed #e2e8f0;
        color: #94a3b8;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .hero-panel {
            grid-template-columns: 1fr;
            padding: 32px;
            text-align: center;
        }
        
        .hero-copy { align-items: center; }
        
        .hero-visual {
            height: 200px;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            transform: rotate(0);
        }

        .timeline-item {
            grid-template-columns: 1fr; /* Stack time and details */
            gap: 8px;
            padding: 16px;
            border: 1px solid #f1f5f9;
            border-left-width: 4px;
        }
        
        .timeline-item .time {
            color: #2563eb;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 640px) {
        .dashboard-content { gap: 24px; }
        .hero-panel h1 { font-size: 1.75rem; }
        .stat-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
@php
    use Illuminate\Support\Str;

    $firstName = $tutor?->name ? Str::of($tutor->name)->before(' ') : 'Tutor';
    $heroImage = $tutorProfile?->banner_path
        ? asset('storage/' . $tutorProfile->banner_path)
        : config('mayclass.images.tutor.banner.fallback');
@endphp

<div class="dashboard-content">
    {{-- Hero Section --}}
    <section class="hero-panel" aria-labelledby="hero-title">
        <div class="hero-copy">
            <h1 id="hero-title">Halo {{ $firstName }},<br>Mari optimalkan sesi hari ini.</h1>
            <p>
                Pantau jadwal, materi, dan perkembangan siswa secara real-time agar pengalaman belajar
                tetap konsisten dan terarah.
            </p>
        </div>
    </section>

    {{-- Highlight Stats --}}
    <section class="stat-grid" aria-label="Sorotan Statistik">
        @foreach ($highlightStats as $stat)
            @php
                $label = trim($stat['label'] ?? '');
                $accent = $stat['accent'] ?? 'blue';

                // Khusus: dilarang orange/purple untuk Materi Aktif & (Quiz/Kuis) Siap Pakai
                $normalized = Str::lower($label);
                $isMateriAktif = $normalized === 'materi aktif';
                $isQuizSiapPakai = in_array($normalized, ['quiz siap pakai', 'kuis siap pakai']);

                if (($isMateriAktif || $isQuizSiapPakai) && in_array($accent, ['orange', 'purple'])) {
                    $accent = 'blue';
                }
            @endphp
            <article class="stat-card accent-{{ $accent }}">
                <div class="value-row">
                    <strong>{{ $stat['display'] }}</strong>
                    @if (!empty($stat['suffix']))
                        <span class="suffix">{{ $stat['suffix'] }}</span>
                    @endif
                </div>
                <span class="label">{{ $stat['label'] }}</span>
                @if (!empty($stat['description']))
                    <p>{{ $stat['description'] }}</p>
                @endif
            </article>
        @endforeach
    </section>

    {{-- Agenda Hari Ini --}}
    <section class="schedule-card" aria-labelledby="agenda-title">
        <div class="schedule-header">
            <div>
                <h2 id="agenda-title">Agenda Mengajar</h2>
                <span>{{ $todayLabel }}</span>
            </div>
        </div>

        @if ($todaySessions->isEmpty())
            <div class="empty-timeline">
                Belum ada sesi mengajar untuk hari ini. <br>
                Istirahat yang cukup atau persiapkan materi untuk esok hari.
            </div>
        @else
            <ul class="timeline">
                @foreach ($todaySessions as $session)
                    <li class="timeline-item {{ !empty($session['highlight']) ? 'is-highlight' : '' }}">
                        <div class="time">{{ $session['time_range'] }}</div>
                        <div class="details">
                            <strong>{{ $session['title'] }}</strong>
                            
                            {{-- Sedikit perapihan HTML structure agar CSS pseudo-element jalan --}}
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                <span>{{ $session['subject'] }} ({{ $session['class_level'] }})</span>
                                <span>{{ $session['location'] }}</span>
                                <span>
                                    {{ $session['student_count'] ? $session['student_count'] . ' Siswa' : 'Jumlah siswa -' }}
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</div>
@endsection