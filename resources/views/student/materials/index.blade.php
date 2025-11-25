@extends('student.layouts.app')

@section('title', 'Materi Pembelajaran')

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

        /* --- Layout Container Full Width --- */
        .materials-container {
            width: 100%;
            padding: 0 40px; /* Jarak aman kiri kanan */
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        /* --- Hero Section --- */
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

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-hero {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
            backdrop-filter: blur(4px);
        }
        .btn-hero:hover { background: white; color: var(--primary); }

        /* --- Section Title --- */
        .section-title {
            text-align: left; /* Rata kiri agar sesuai layout lebar */
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        .section-title h2 { font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0 0 4px; }
        .section-title p { color: var(--text-muted); margin: 0; font-size: 0.95rem; }

        /* --- Collections Grid --- */
        .collections-grid {
            display: grid;
            /* Grid otomatis mengisi lebar, minimal 400px per kartu */
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 32px;
        }

        /* --- Collection Card --- */
        .collection-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .collection-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: #cbd5e1;
        }

        /* Header Kartu */
        .collection-header {
            padding: 20px 24px;
            background: var(--bg-body);
            position: relative;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .collection-badge {
            background: white;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .collection-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }
        
        /* Body Kartu (List) */
        .collection-body {
            padding: 0;
            background: var(--surface);
            flex: 1;
        }

        /* Material Item Row */
        .material-item {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
        }
        .material-item:last-child { border-bottom: none; }
        .material-item:hover { background: #fcfcfc; }

        .material-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }
        
        .material-info h4 {
            margin: 0 0 4px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-main);
        }
        
        .material-info p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .material-tag {
            background: var(--bg-body);
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            border: 1px solid var(--border);
            white-space: nowrap;
        }

        .material-meta {
            display: flex;
            gap: 16px;
            font-size: 0.85rem;
            color: var(--text-muted);
            align-items: center;
            font-weight: 500;
        }

        .material-actions {
            display: flex;
            gap: 8px;
            margin-top: 6px;
        }

        .btn-sm {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }
        
        .btn-primary-sm {
            background: var(--primary);
            color: white;
        }
        .btn-primary-sm:hover { background: var(--primary-hover); }
        
        .btn-outline-sm {
            border: 1px solid var(--border);
            color: var(--text-main);
            background: white;
        }
        .btn-outline-sm:hover { border-color: var(--primary); color: var(--primary); }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 0;
            background: var(--surface);
            border: 1px dashed var(--border);
            border-radius: var(--radius-lg);
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .materials-container { padding: 0 20px; }
            .hero-banner { padding: 24px; }
            .hero-title { font-size: 1.5rem; }
            .collections-grid { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    <div class="materials-container">

        {{-- 1. Hero Section --}}
        <div class="hero-banner">
            <div class="hero-content">
                <h1 class="hero-title">Mulai Belajar</h1>
                <p class="hero-desc">
                    @if (! empty($activePackage))
                        Materi eksklusif untuk paket <strong>{{ $activePackage->detail_title ?? $activePackage->title }}</strong>.
                    @endif
                    Terdapat {{ number_format($stats['total']) }} materi aktif yang mencakup {{ number_format($stats['subjects']) }} mata pelajaran 
                    dan {{ number_format(count($stats['levels'])) }} jenjang belajar untuk mendukung prestasimu.
                </p>
                <div class="hero-actions">
                    <a href="{{ $materialsLink }}" target="_blank" rel="noopener" class="btn-hero">
                        Buka Semua Materi
                    </a>
                    <a href="{{ route('student.dashboard') }}" class="btn-hero">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. Section Header --}}
        <div class="section-title">
            <h2>Materi Pembelajaran & Bank Soal</h2>
            <p>Kuasai materi pelajaran serta persiapan ujian dengan metode yang terstruktur.</p>
        </div>

        {{-- 3. Materials Grid --}}
        @if ($collections->isNotEmpty())
            <div class="collections-grid">
                @foreach ($collections as $collection)
                    <article class="collection-card">
                        {{-- Header Kartu --}}
                        <div class="collection-header" style="border-left: 4px solid {{ $collection['accent'] }};">
                            <h3 class="collection-title" style="color: var(--text-main);">{{ $collection['label'] }}</h3>
                            <span class="collection-badge">{{ count($collection['items']) }} BAB</span>
                        </div>

                        <div class="collection-body">
                            @foreach ($collection['items'] as $material)
                                <div class="material-item">
                                    <div class="material-top">
                                        <div class="material-info">
                                            <h4>{{ $material['title'] }}</h4>
                                            <p>{{ Str::limit($material['summary'], 100) }}</p>
                                        </div>
                                        <span class="material-tag">{{ $material['level'] }}</span>
                                    </div>
                                    
                                    <div class="material-meta">
                                        <span>{{ $material['chapter_count'] }} Bab</span>
                                        <span>{{ $material['objective_count'] }} Tujuan Pembelajaran</span>
                                    </div>

                                    <div class="material-actions">
                                        <a href="{{ route('student.materials.show', $material['slug']) }}" class="btn-sm btn-primary-sm">
                                            Buka Materi
                                        </a>
                                        <a href="{{ $material['view_url'] }}" target="_blank" rel="noopener" class="btn-sm btn-outline-sm">
                                            Lihat Sumber
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            @if(count($collection['items']) === 0)
                                <div style="text-align: center; padding: 32px; color: var(--text-muted); font-size: 0.9rem;">
                                    Belum ada materi di kategori ini.
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h3>Belum ada materi tercatat</h3>
                <p>Materi pembelajaran akan muncul di sini setelah diterbitkan oleh tutor atau admin.</p>
                <div style="margin-top: 20px;">
                    <a href="{{ $materialsLink }}" target="_blank" rel="noopener" class="btn-sm btn-primary-sm">
                        Cek Google Drive
                    </a>
                </div>
            </div>
        @endif

    </div>
@endsection