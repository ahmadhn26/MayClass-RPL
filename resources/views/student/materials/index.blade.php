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
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* --- Layout Container Full Width --- */
        .materials-container {
            width: 100%;
            padding: 0 40px;
            /* Jarak aman kiri kanan */
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

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-hero {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
            backdrop-filter: blur(4px);
        }

        .btn-hero:hover {
            background: white;
            color: var(--primary);
        }

        /* --- Section Title --- */
        .section-title {
            text-align: left;
            /* Rata kiri agar sesuai layout lebar */
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
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

        .material-item:last-child {
            border-bottom: none;
        }

        .material-item:hover {
            background: #fcfcfc;
        }

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

        .btn-primary-sm:hover {
            background: var(--primary-hover);
        }

        .btn-outline-sm {
            border: 1px solid var(--border);
            color: var(--text-main);
            background: white;
        }

        .btn-outline-sm:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

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
            .materials-container {
                padding: 0 20px;
            }

            .hero-banner {
                padding: 24px;
            }

            .hero-title {
                font-size: 1.5rem;
            }

            .collections-grid {
                grid-template-columns: 1fr;
            }
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
                    @if (!empty($activePackage))
                        Materi eksklusif untuk paket
                        <strong>{{ $activePackage->detail_title ?? $activePackage->title }}</strong>.
                    @endif
                    Terdapat {{ number_format($stats['total']) }} materi aktif yang mencakup
                    {{ number_format($stats['subjects']) }} mata pelajaran
                    dan {{ number_format(count($stats['levels'])) }} jenjang belajar untuk mendukung prestasimu.
                </p>
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
                                            <h4>📁 {{ $material['title'] }}</h4>
                                            <p>{{ Str::limit($material['summary'], 100) }}</p>
                                        </div>
                                        <span class="material-tag">{{ $material['level'] }}</span>
                                    </div>

                                    <div class="material-meta">
                                        <span>📄 {{ $material['item_count'] }} Materi</span>
                                    </div>

                                    <div class="material-actions">
                                        <button onclick="openFolderPreview({{ $material['id'] }})" class="btn-sm btn-primary-sm">
                                            Lihat Materi
                                        </button>
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
        @endif

        </div>

        {{-- Preview Modal --}}
        <div id="folderPreviewModal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
            <div
                style="background: white; border-radius: 16px; max-width: 700px; width: 90%; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                <div
                    style="padding: 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <h2 id="folderTitle" style="margin: 0; font-size: 1.5rem; font-weight: 700;">Preview Folder</h2>
                    <button onclick="closeFolderPreview()"
                        style="border: none; background: none; font-size: 1.5rem; color: #94a3b8; cursor: pointer; padding: 0; width: 32px; height: 32px;">&times;</button>
                </div>
                <div style="padding: 24px; overflow-y: auto; flex: 1;">
                    <div id="folderItemsList"></div>
                </div>
            </div>
        </div>

        <script>
            // Preview Modal Functions
            window.openFolderPreview = function (materialId) {
                const modal = document.getElementById('folderPreviewModal');
                const itemsList = document.getElementById('folderItemsList');
                const folderTitle = document.getElementById('folderTitle');

                // Show modal
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';

                // Loading
                itemsList.innerHTML = '<p style="text-align: center; color: #94a3b8;">Memuat...</p>';

                // Get materials data
                const materials = @json($materials);
                const material = materials.find(m => m.id === materialId);

                if (!material) {
                    itemsList.innerHTML = '<p style="color: #ef4444;">Folder tidak ditemukan</p>';
                    return;
                }

                // Update title
                folderTitle.textContent = material.title;

                // Display items
                if (material.material_items && material.material_items.length > 0) {
                    let html = '';
                    material.material_items.forEach((item, index) => {
                        html += `
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 16px;">
                                <h3 style="margin: 0 0 12px 0; font-size: 1.1rem; color: #0f172a; font-weight: 600;">${index + 1}. ${item.name}</h3>
                                <p style="color: #64748b; margin: 0 0 16px 0; line-height: 1.6;">${item.description}</p>
                                <a href="${item.link}" target="_blank" rel="noopener" 
                                   style="display: inline-block; background: #0f766e; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: background 0.2s;">
                                    🔗 Buka Materi
                                </a>
                            </div>
                        `;
                    });
                    itemsList.innerHTML = html;
                } else {
                    itemsList.innerHTML = '<p style="text-align: center; color: #94a3b8;">Folder ini belum memiliki materi</p>';
                }
            };

            window.closeFolderPreview = function () {
                const modal = document.getElementById('folderPreviewModal');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            };

            // Close modal when clicking outside
            document.getElementById('folderPreviewModal')?.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeFolderPreview();
                }
            });

            // Auto-open preview if 'preview' parameter exists in URL
            window.addEventListener('DOMContentLoaded', function () {
                const urlParams = new URLSearchParams(window.location.search);
                const previewId = urlParams.get('preview');

                if (previewId) {
                    // Wait a bit for the page to fully load
                    setTimeout(() => {
                        window.openFolderPreview(parseInt(previewId));
                        // Remove the parameter from URL without reloading
                        const newUrl = window.location.pathname;
                        window.history.replaceState({}, '', newUrl);
                    }, 300);
                }
            });
        </script>
@endsection