@extends('tutor.layout')

@section('title', 'Manajemen Materi - MayClass')

@push('styles')
    <style>
        :root {
            --primary-color: #3fa67e;
            --primary-dark: #2f8a67;
            --bg-surface: #ffffff;
            --bg-muted: #f8fafc;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-hover: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --radius-lg: 16px;
            --radius-md: 12px;
        }

        /* --- Header Section --- */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 40px;
        }

        .header-title h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
            letter-spacing: -0.025em;
        }

        .stats-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            background: #ecfdf5;
            color: var(--primary-dark);
            border-radius: 99px;
            font-size: 0.875rem;
            font-weight: 600;
            border: 1px solid #d1fae5;
        }

        .btn-add {
            background: var(--primary-color);
            color: white;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(63, 166, 126, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-add:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(63, 166, 126, 0.4);
        }

        /* --- Toolbar & Search --- */
        .content-toolbar {
            margin-bottom: 32px;
        }

        .search-wrapper {
            position: relative;
            max-width: 480px;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 14px 50px 14px 20px; /* Space for icon on right */
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--bg-surface);
            font-size: 0.95rem;
            transition: all 0.2s;
            box-shadow: var(--shadow-sm);
            font-family: inherit;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(63, 166, 126, 0.15);
        }

        .search-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            color: #64748b;
            transition: color 0.2s;
        }

        .search-btn:hover {
            color: var(--primary-color);
            background: #f1f5f9;
        }

        /* --- Material Grid --- */
        .material-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .material-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            gap: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .material-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: #cbd5e1;
        }

        .material-thumbnail {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            border-radius: 12px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0; /* Prevent flex text overflow */
        }

        .card-title {
            margin: 0 0 8px 0;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.4;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tags-row {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 0.75rem;
        }

        .tag {
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .tag-subject { background: #eff6ff; color: #2563eb; }
        .tag-level { background: #f5f3ff; color: #7c3aed; }

        .card-summary {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0 0 16px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-actions {
            margin-top: auto;
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            text-align: center;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }
        .btn-secondary:hover { background: #e2e8f0; color: #1e293b; }

        .btn-outline {
            border: 1px solid var(--border-color);
            color: #475569;
            background: white;
        }
        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: #ecfdf5;
        }

        /* --- Alerts & States --- */
        .system-alert {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            color: #92400e;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .empty-state {
            text-align: center;
            padding: 64px 20px;
            background: var(--bg-surface);
            border: 2px dashed var(--border-color);
            border-radius: var(--radius-lg);
            color: #64748b;
        }

        .empty-state strong {
            display: block;
            font-size: 1.25rem;
            color: #1e293b;
            margin-bottom: 8px;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .material-card {
                flex-direction: column;
            }
            .material-thumbnail {
                width: 100%;
                height: 160px;
            }
            .page-header {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-add {
                justify-content: center;
            }
            .card-actions {
                flex-direction: row;
            }
            .action-btn {
                flex: 1;
            }
        }
    </style>
@endpush

@section('content')
    @php($tableReady = $tableReady ?? true)

    {{-- Header Section --}}
    <div class="page-header">
        <div class="header-title">
            <h1>Manajemen Materi</h1>
            <div class="stats-badge">
                {{ $tableReady ? $materials->count() : 0 }} Materi Aktif
            </div>
        </div>
        <a href="{{ route('tutor.materials.create') }}" class="btn-add">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Materi
        </a>
    </div>

    {{-- Search Toolbar --}}
    <div class="content-toolbar">
        <form method="GET" class="search-wrapper">
            <input 
                type="search" 
                name="q" 
                class="search-input" 
                value="{{ $search }}" 
                placeholder="Cari judul, mata pelajaran, atau jenjang..." 
            />
            <button type="submit" class="search-btn" title="Cari">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    {{-- Content Area --}}
    @if (! $tableReady)
        <div class="system-alert">
            <strong>⚠️ Database materi belum siap</strong>
            <p style="margin: 8px 0;">Jalankan migrasi agar tutor dapat mengelola materi.</p>
            <code style="background: rgba(255,255,255,0.6); padding: 2px 6px; border-radius: 4px;">php artisan migrate</code>
        </div>
    @elseif ($materials->isEmpty())
        <div class="empty-state">
            <div style="font-size: 3rem; margin-bottom: 16px;">📚</div>
            <strong>Belum ada materi terdaftar</strong>
            <p>Mulai tambahkan materi pertama Anda untuk membagikan modul belajar kepada siswa.</p>
        </div>
    @else
        <div class="material-grid">
            @foreach ($materials as $material)
                <article class="material-card">
                    <img src="{{ $material->thumbnail_asset }}" alt="{{ $material->title }}" class="material-thumbnail" />
                    
                    <div class="card-content">
                        <h3 class="card-title" title="{{ $material->title }}">{{ $material->title }}</h3>
                        
                        <div class="tags-row">
                            <span class="tag tag-subject">{{ $material->subject->name ?? 'Tanpa Mapel' }}</span>
                            <span class="tag tag-level">{{ $material->level }}</span>
                        </div>

                        <p class="card-summary">{{ Str::limit($material->summary, 100) }}</p>

                        <div class="card-actions">
                            <a href="{{ route('tutor.materials.edit', $material) }}" class="action-btn btn-secondary">
                                Edit
                            </a>
                            @if ($material->resource_path)
                                @php($isExternal = str_starts_with($material->resource_path, 'http'))
                                <a href="{{ $isExternal ? $material->resource_path : route('tutor.materials.preview', $material->slug) }}"
                                   class="action-btn btn-outline"
                                   target="_blank" rel="noopener">
                                   Preview
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection