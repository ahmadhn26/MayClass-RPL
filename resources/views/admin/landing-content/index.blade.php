@extends('admin.layout')

@section('title', 'Manajemen Content - MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --primary-light: #ccfbf1;
            --primary-dark: #115e59;
            --bg-surface: #ffffff;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        .content-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* --- HEADER (Matches Finance) --- */
        .page-header {
            background: var(--bg-surface);
            padding: 24px 32px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 4px 0;
        }

        .header-content p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* --- SECTIONS --- */
        .section-block {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::before {
            content: '';
            display: block;
            width: 4px;
            height: 16px;
            background: var(--primary);
            border-radius: 2px;
        }

        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-add:hover {
            background: var(--primary-dark);
        }

        /* --- CARDS GRID (Compact) --- */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .content-card {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .content-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .card-img-wrapper {
            position: relative;
            height: 140px;
            overflow: hidden;
            background: var(--bg-body);
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-body {
            padding: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            line-height: 1.3;
        }

        .card-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: auto;
            padding-top: 8px;
        }

        .meta-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
        }

        .meta-info {
            display: flex;
            flex-direction: column;
        }

        .meta-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .meta-role {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .card-actions {
            padding: 8px 12px;
            border-top: 1px solid var(--border-color);
            background: #f8fafc;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            color: var(--text-muted);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .btn-icon:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-main);
        }

        .btn-icon.text-danger:hover {
            color: #ef4444;
            background: #fef2f2;
        }

        .empty-state {
            grid-column: 1 / -1;
            padding: 24px;
            text-align: center;
            background: var(--bg-body);
            border-radius: var(--radius);
            border: 1px dashed var(--border-color);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* --- GLASSMORPHISM MODAL --- */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 20px;
            padding: 24px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.95);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-backdrop.active .modal-glass {
            transform: scale(1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-muted);
            cursor: pointer;
            line-height: 1;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: #fff;
            font-family: inherit;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(15, 118, 110, 0.2);
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }

        .file-upload-zone {
            border: 2px dashed var(--border-color);
            border-radius: var(--radius);
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .file-upload-zone:hover,
        .file-upload-zone.dragover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .file-upload-text {
            color: var(--text-muted);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .file-preview {
            margin-top: 12px;
            display: none;
            align-items: center;
            gap: 12px;
            padding: 8px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }

        .file-preview.active {
            display: flex;
        }

        .file-info {
            flex: 1;
            font-size: 0.85rem;
            color: var(--text-main);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-error {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 4px;
            display: none;
        }

        /* --- COMPACT PREVIEWS --- */
        .preview-card-hero {
            position: relative;
            height: 120px;
            border-radius: var(--radius);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            background-size: cover;
            background-position: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .preview-card-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
            z-index: 1;
        }

        .preview-hero-content {
            position: relative;
            z-index: 2;
            padding: 16px;
            width: 100%;
        }

        .preview-card-compact {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            padding: 12px;
            display: flex;
            gap: 12px;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .preview-card-compact:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .preview-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            background: var(--bg-body);
        }

        .preview-img-square {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: var(--bg-body);
        }

        .preview-info {
            flex: 1;
            min-width: 0;
            /* Fix truncation */
        }

        .preview-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 2px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .preview-subtitle {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }

        .preview-actions {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-left: auto;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- Documentation Modal --}}
    <div id="docModal" class="modal-backdrop">
        <div class="modal-glass">
            <div class="modal-header">
                <h2 id="docModalTitle" class="modal-title">Tambah Dokumentasi</h2>
                <button type="button" onclick="closeDocModal()" class="btn-close">&times;</button>
            </div>
            <form id="docForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="docMethod" name="_method" value="POST">

                <div class="form-group">
                    <label class="form-label">Tanggal Kegiatan *</label>
                    <input type="date" id="docDate" name="activity_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kesan Singkat *</label>
                    <textarea id="docDescription" name="description" class="form-control" rows="3" required
                        placeholder="Ceritakan kesan selama kegiatan belajar mengajar..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Kegiatan *</label>
                    <div class="file-upload-zone" onclick="document.getElementById('docPhoto').click()">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="margin: 0 auto 12px; color: var(--text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <p class="file-upload-text">Klik untuk upload foto<br><small>JPG, PNG (Max 5MB)</small></p>
                        <input type="file" id="docPhoto" name="photo" accept="image/*" style="display: none;"
                            onchange="previewDocImage(this)">
                    </div>
                    <img id="docPreview"
                        style="display: none; max-width: 100%; max-height: 200px; border-radius: 8px; margin-top: 12px;">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeDocModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Documentation Modal Functions
        function openDocModal(mode, data = null) {
            const modal = document.getElementById('docModal');
            const form = document.getElementById('docForm');
            const title = document.getElementById('docModalTitle');
            const method = document.getElementById('docMethod');
            const preview = document.getElementById('docPreview');
            const photoInput = document.getElementById('docPhoto');

            if (mode === 'create') {
                title.textContent = 'Tambah Dokumentasi';
                form.action = '{{ route("admin.documentations.store") }}';
                method.value = 'POST';
                form.reset();
                preview.style.display = 'none';
                photoInput.required = true;
            } else if (mode === 'edit' && data) {
                title.textContent = 'Edit Dokumentasi';
                form.action = `/admin/documentations/${data.id}`;
                method.value = 'PUT';
                document.getElementById('docDate').value = data.activity_date;
                document.getElementById('docDescription').value = data.description;
                preview.src = `{{ asset('storage/') }}/${data.photo_path}`;
                preview.style.display = 'block';
                photoInput.required = false;
            }

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeDocModal() {
            const modal = document.getElementById('docModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function previewDocImage(input) {
            const preview = document.getElementById('docPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function confirmDeleteDoc(form, date) {
            Swal.fire({
                title: 'Hapus Dokumentasi?',
                text: `Dokumentasi tanggal ${date} akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Close modal on backdrop click
        document.getElementById('docModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeDocModal();
            }
        });
    </script>
@endpush

@section('content')
    <div class="content-container">

        {{-- Header --}}
        <div class="page-header">
            <div class="header-content">
                <h2>Manajemen Content</h2>
                <p>Kelola konten landing page MayClass secara dinamis dan real-time.</p>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Hero Section</h3>
                @if(($contents['hero'] ?? collect())->isEmpty())
                    <button onclick="openModal('create', 'hero')" class="btn-add">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                @endif
            </div>
            <div class="content-grid" style="grid-template-columns: 1fr;">
                @forelse($contents['hero'] ?? [] as $item)
                    @php
                        $heroImg = $item->image
                            ? \App\Support\AvatarResolver::resolve([$item->image])
                            : asset('images/stis_contoh.jpeg');
                    @endphp
                    <div class="preview-card-hero" style="background-image: url('{{ $heroImg }}');">
                        <div class="preview-hero-content">
                            <h4 style="margin: 0; font-size: 1.2rem; font-weight: 700;">
                                {{ $item->content['subtitle'] ?? 'No Title' }}
                            </h4>
                            <span style="font-size: 0.8rem; opacity: 0.9;">{{ $item->content['title'] ?? '' }}</span>
                        </div>
                        <div style="position: absolute; top: 12px; right: 12px; z-index: 3; display: flex; gap: 8px;">
                            <button onclick="openModal('edit', 'hero', {{ $item }})" class="btn-icon"
                                style="background: rgba(255,255,255,0.9);">Edit</button>
                            <button type="button" class="btn-icon text-danger btn-delete"
                                style="background: rgba(255,255,255,0.9);" data-id="{{ $item->id }}"
                                data-name="{{ $item->content['subtitle'] ?? 'Hero Content' }}"
                                data-action="{{ route('admin.landing-content.destroy', $item->id) }}">
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada konten Hero.</div>
                @endforelse
            </div>
        </div>

        {{-- Articles Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Artikel / Wawasan</h3>
                <button onclick="openModal('create', 'article')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid">
                @forelse($contents['article'] ?? [] as $item)
                    <div class="content-card">
                        @if($item->image)
                            @php
                                $articleImg = \App\Support\AvatarResolver::resolve([$item->image]) ?? asset($item->image);
                            @endphp
                            <div class="card-img-wrapper">
                                <img src="{{ $articleImg }}" alt="Thumbnail" class="card-img">
                            </div>
                        @endif
                        <div class="card-body">
                            <h4 class="card-title">{{ $item->content['title'] ?? '' }}</h4>
                            <p class="card-desc">{{ Str::limit($item->content['description'] ?? '', 60) }}</p>
                            @if(!empty($item->content['link']))
                                <a href="{{ $item->content['link'] }}" target="_blank"
                                    style="font-size: 0.75rem; color: var(--primary); text-decoration: none; margin-top: auto;">
                                    Link: {{ Str::limit($item->content['link'], 30) }}
                                </a>
                            @endif
                        </div>
                        <div class="card-actions">
                            <button onclick="openModal('edit', 'article', {{ $item }})" class="btn-icon">Edit</button>
                            <button type="button" class="btn-icon text-danger btn-delete" data-id="{{ $item->id }}"
                                data-name="{{ $item->content['title'] ?? 'Artikel' }}"
                                data-action="{{ route('admin.landing-content.destroy', $item->id) }}">
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada artikel.</div>
                @endforelse
            </div>
        </div>

        {{-- Dokumentasi Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Dokumentasi Foto Kegiatan</h3>
                <button onclick="openDocModal('create')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid">
                @php
                    $weekNumber = now()->weekOfYear;
                    $year = now()->year;
                    $recentDocs = \App\Models\Documentation::where('is_active', true)
                        ->orderBy('order', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->limit(12)
                        ->get();
                @endphp
                @forelse($recentDocs as $doc)
                    @php
                        $docImg = \App\Support\AvatarResolver::resolve([$doc->photo_path]) ?? asset('storage/' . $doc->photo_path);
                    @endphp
                    <div class="content-card">
                        <div class="card-img-wrapper">
                            <img src="{{ $docImg }}" alt="Dokumentasi" class="card-img">
                        </div>
                        <div class="card-body">
                            <h4 class="card-title" style="color: var(--primary); font-size: 0.85rem;">
                                📅 {{ $doc->activity_date->locale('id')->translatedFormat('d F Y') }}
                            </h4>
                            <p class="card-desc">{{ Str::limit($doc->description, 80) }}</p>
                        </div>
                        <div class="card-actions">
                            <button onclick='openDocModal("edit", {{ $doc }})' class="btn-icon">Edit</button>
                            <form action="{{ route('admin.documentations.destroy', $doc->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-icon text-danger"
                                    onclick="confirmDeleteDoc(this.form, '{{ $doc->activity_date->format('d M Y') }}')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada dokumentasi.</div>
                @endforelse
            </div>
            @if($recentDocs->count() > 0)
                <div style="text-align: center; margin-top: 12px;">
                    <small style="color: var(--text-muted);">{{ $recentDocs->count() }} dokumentasi ditampilkan</small>
                </div>
            @endif
        </div>

        {{-- Features Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Keunggulan (Highlight)</h3>
                <button onclick="openModal('create', 'feature')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid">
                @forelse($contents['feature'] ?? [] as $item)
                    <div class="preview-card-compact">
                        <div class="preview-info">
                            <h4 class="preview-title">{{ $item->content['title'] ?? '' }}</h4>
                            <p class="preview-subtitle">{{ $item->content['description'] ?? '' }}</p>
                        </div>
                        <div class="preview-actions">
                            <button onclick="openModal('edit', 'feature', {{ $item }})" class="btn-icon">Edit</button>
                            <button type="button" class="btn-icon text-danger btn-delete" data-id="{{ $item->id }}"
                                data-name="{{ $item->content['title'] ?? 'Keunggulan' }}"
                                data-action="{{ route('admin.landing-content.destroy', $item->id) }}">
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada keunggulan.</div>
                @endforelse
            </div>
        </div>

        {{-- Testimonials Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Testimoni</h3>
                <button onclick="openModal('create', 'testimonial')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid">
                @forelse($contents['testimonial'] ?? [] as $item)
                    @php
                        $testiImg = $item->image ? \App\Support\AvatarResolver::resolve([$item->image]) : null;
                    @endphp
                            <div class="preview-card-compact">
                                @if($testiImg)
                                    <img src="{{ $testiImg }}" alt="Avatar" class="preview-avatar">
                                @else
                                    <div class="preview-avatar" style="background: #e2e8f0;"></div>
                                @endif
                                <div class="preview-info">
                                    <h4 class="preview-title">{{ $item->content['name'] ?? '' }}</h4>
                                    <p class="preview-subtitle" style="font-style: italic;">"{{ $item->content['quote'] ?? '' }}"</p>
                                </div>
                                <div class="preview-actions">
                                    <button onclick="openModal('edit', 'testimonial', {{ $item }})" class="btn-icon">Edit</button>
                                    <button type="button" class="btn-icon text-danger btn-delete" data-id="{{ $item->id }}"
                                        data-name="{{ $item->content['name'] ?? 'Testimoni' }}"
                                        data-action="{{ route('admin.landing-content.destroy', $item->id) }}">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                @empty
                        <div class="empty-state">Belum ada testimoni.</div>
                    @endforelse
                </div>
            </div>

            {{-- Mentors Section --}}
            <div class="section-block">
                <div class="section-header">
                    <h3 class="section-title">Mentor</h3>
                    <button onclick="openModal('create', 'mentor')" class="btn-add">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="content-grid">
                    @forelse($contents['mentor'] ?? [] as $item)
                        @php
                            $mentorImg = $item->image ? \App\Support\AvatarResolver::resolve([$item->image]) : null;
                        @endphp
                        <div class="preview-card-compact">
                            @if($mentorImg)
                                <img src="{{ $mentorImg }}" alt="Mentor" class="preview-img-square">
                            @else
                                <div class="preview-img-square" style="background: #e2e8f0;"></div>
                            @endif
                            <div class="preview-info">
                                <h4 class="preview-title">{{ $item->content['name'] ?? '' }}</h4>
                                <p class="preview-subtitle" style="color: var(--primary); font-weight: 500;">
                                    {{ $item->content['role'] ?? '' }}
                                </p>
                                <div style="display: flex; gap: 4px; flex-wrap: wrap; margin-top: 4px;">
                                    @foreach($item->content['meta'] ?? [] as $meta)
                                        <span
                                            style="font-size: 0.7rem; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">{{ $meta }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="preview-actions">
                                <button onclick="openModal('edit', 'mentor', {{ $item }})" class="btn-icon">Edit</button>
                                <button type="button" class="btn-icon text-danger btn-delete" data-id="{{ $item->id }}"
                                    data-name="{{ $item->content['name'] ?? 'Mentor' }}"
                                    data-action="{{ route('admin.landing-content.destroy', $item->id) }}">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Belum ada mentor.</div>
                    @endforelse
                </div>
            </div>

            {{-- FAQ Section --}}
            <div class="section-block">
                <div class="section-header">
                    <h3 class="section-title">FAQ</h3>
                    <button onclick="openModal('create', 'faq')" class="btn-add">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="content-grid" style="grid-template-columns: 1fr;">
                    @forelse($contents['faq'] ?? [] as $item)
                        <div class="content-card" style="flex-direction: row; align-items: center;">
                            <div class="card-body">
                                <h4 class="card-title" style="color: var(--primary); font-size: 0.9rem;">Q:
                                    {{ $item->content['question'] ?? '' }}
                                </h4>
                                <p class="card-desc" style="margin-top: 2px;">A: {{ $item->content['answer'] ?? '' }}</p>
                            </div>
                            <div class="card-actions"
                                style="border-top: none; background: transparent; border-left: 1px solid var(--border-color);">
                                <button onclick="openModal('edit', 'faq', {{ $item }})" class="btn-icon">Edit</button>
                                <button type="button" class="btn-icon text-danger btn-delete" data-id="{{ $item->id }}"
                                    data-name="FAQ" data-action="{{ route('admin.landing-content.destroy', $item->id) }}">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Belum ada FAQ.</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Glassmorphism Modal --}}
        <div id="contentModal" class="modal-backdrop">
            <div class="modal-glass">
                <div class="modal-header">
                    <h2 id="modalTitle" class="modal-title">Tambah Konten</h2>
                    <button type="button" onclick="closeModal()" class="btn-close">&times;</button>
                </div>
                <form id="contentForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="methodField"></div>
                    <input type="hidden" name="section" id="sectionInput">

                    <div id="dynamicFields">
                        <!-- Fields injected via JS -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="closeModal()" class="btn-cancel">Batal</button>
                        <button type="submit" class="btn-submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('contentModal');
        const form = document.getElementById('contentForm');
        const methodField = document.getElementById('methodField');
        const dynamicFields = document.getElementById('dynamicFields');
        const sectionInput = document.getElementById('sectionInput');
        const modalTitle = document.getElementById('modalTitle');

        function openModal(mode, section, data = null) {
            modal.classList.add('active');
            sectionInput.value = section;
            dynamicFields.innerHTML = '';

            // Map section to readable name
            const sectionNames = {
                'hero': 'Hero Section',
                'article': 'Artikel',
                'feature': 'Keunggulan',
                'testimonial': 'Testimoni',
                'mentor': 'Mentor',
                'faq': 'FAQ'
            };
            const sectionName = sectionNames[section] || 'Konten';

            if (mode === 'edit') {
                modalTitle.textContent = `Edit ${sectionName}`;
                form.action = `/admin/landing-content/${data.id}`;
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            } else {
                modalTitle.textContent = `Tambah ${sectionName}`;
                form.action = "{{ route('admin.landing-content.store') }}";
                methodField.innerHTML = '';
            }

            // Generate Fields based on Section
            let fields = [];

            if (section === 'hero') {
                fields = [
                    { name: 'content[title]', label: 'Judul Badge (Kecil)', type: 'text', value: data?.content?.title },
                    { name: 'content[subtitle]', label: 'Judul Utama (Besar)', type: 'text', value: data?.content?.subtitle },
                    { name: 'content[description]', label: 'Deskripsi', type: 'textarea', value: data?.content?.description },
                    { name: 'image', label: 'Background Image', type: 'file' },
                ];
            } else if (section === 'article') {
                fields = [
                    { name: 'content[title]', label: 'Judul Artikel', type: 'text', value: data?.content?.title },
                    { name: 'content[description]', label: 'Deskripsi Singkat', type: 'textarea', value: data?.content?.description },
                    { name: 'content[link]', label: 'Link Artikel (URL)', type: 'url', value: data?.content?.link, placeholder: 'https://...' },
                ];
            } else if (section === 'feature') {
                fields = [
                    { name: 'content[title]', label: 'Judul Keunggulan', type: 'text', value: data?.content?.title },
                    { name: 'content[description]', label: 'Deskripsi', type: 'textarea', value: data?.content?.description },
                ];
            } else if (section === 'testimonial') {
                fields = [
                    { name: 'content[name]', label: 'Nama Siswa / Nama Orang tua Siswa', type: 'text', value: data?.content?.name },
                    { name: 'content[role]', label: 'Status/Role (ex: Lulus UI)', type: 'text', value: data?.content?.role },
                    { name: 'content[quote]', label: 'Testimoni', type: 'textarea', value: data?.content?.quote },
                    { name: 'image', label: 'Foto Profil', type: 'file' },
                ];
            } else if (section === 'mentor') {
                fields = [
                    { name: 'content[name]', label: 'Nama Mentor', type: 'text', value: data?.content?.name },
                    { name: 'content[role]', label: 'Bidang Studi', type: 'text', value: data?.content?.role },
                    { name: 'content[quote]', label: 'Kutipan', type: 'textarea', value: data?.content?.quote },
                    {
                        name: 'content[meta]',
                        label: 'Info Tambahan',
                        type: 'dynamic_list',
                        value: data?.content?.meta ?? [data?.content?.meta_1, data?.content?.meta_2].filter(Boolean)
                    },
                    { name: 'image', label: 'Foto Mentor', type: 'file' },
                ];
            } else if (section === 'faq') {
                if (mode === 'create') {
                    fields = [
                        {
                            name: 'items',
                            label: 'Daftar FAQ',
                            type: 'dynamic_group',
                            fields: [
                                { name: 'question', label: 'Pertanyaan', type: 'text' },
                                { name: 'answer', label: 'Jawaban', type: 'textarea' }
                            ]
                        }
                    ];
                } else {
                    fields = [
                        { name: 'content[question]', label: 'Pertanyaan', type: 'text', value: data?.content?.question },
                        { name: 'content[answer]', label: 'Jawaban', type: 'textarea', value: data?.content?.answer },
                    ];
                }
            }

            fields.forEach(field => {
                const wrapper = document.createElement('div');
                wrapper.className = 'form-group';

                const label = document.createElement('label');
                label.className = 'form-label';
                label.textContent = field.label;
                wrapper.appendChild(label);

                if (field.type === 'dynamic_list') {
                    const container = document.createElement('div');
                    container.className = 'dynamic-list-container';

                    const values = Array.isArray(field.value) && field.value.length ? field.value : [''];

                    values.forEach((val, index) => {
                        addListInput(container, field.name + '[]', val);
                    });

                    const addButton = document.createElement('button');
                    addButton.type = 'button';
                    addButton.className = 'btn-add';
                    addButton.style.marginTop = '8px';
                    addButton.textContent = '+ Tambah Item';
                    addButton.onclick = () => addListInput(container, field.name + '[]', '');

                    wrapper.appendChild(container);
                    wrapper.appendChild(addButton);

                } else if (field.type === 'dynamic_group') {
                    const container = document.createElement('div');
                    container.className = 'dynamic-group-container';

                    // Initial empty group
                    addGroupInput(container, field.name, field.fields, 0);

                    const addButton = document.createElement('button');
                    addButton.type = 'button';
                    addButton.className = 'btn-add';
                    addButton.style.marginTop = '8px';
                    addButton.textContent = '+ Tambah Item';
                    addButton.onclick = () => addGroupInput(container, field.name, field.fields);

                    wrapper.appendChild(container);
                    wrapper.appendChild(addButton);
                } else if (field.type === 'file') {
                    const fileWrapper = document.createElement('div');
                    fileWrapper.className = 'file-upload-wrapper';

                    const zone = document.createElement('div');
                    zone.className = 'file-upload-zone';
                    zone.innerHTML = `
                                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 8px; color: var(--text-muted);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                    </svg>
                                                    <div class="file-upload-text">Klik atau drag file ke sini</div>
                                                    <div class="file-upload-text" style="font-size: 0.75rem; margin-top: 4px;">Max size: 10MB</div>
                                                `;

                    const input = document.createElement('input');
                    input.type = 'file';
                    input.name = field.name;
                    input.style.display = 'none';
                    input.accept = 'image/*';

                    const preview = document.createElement('div');
                    preview.className = 'file-preview';

                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'file-info';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn-icon text-danger';
                    removeBtn.textContent = 'X';

                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'file-error';
                    errorMsg.textContent = 'File terlalu besar! Maksimal 10MB.';

                    preview.appendChild(fileInfo);
                    preview.appendChild(removeBtn);

                    // Event Listeners
                    zone.onclick = () => input.click();

                    zone.ondragover = (e) => {
                        e.preventDefault();
                        zone.classList.add('dragover');
                    };

                    zone.ondragleave = () => zone.classList.remove('dragover');

                    zone.ondrop = (e) => {
                        e.preventDefault();
                        zone.classList.remove('dragover');
                        if (e.dataTransfer.files.length) {
                            input.files = e.dataTransfer.files;
                            handleFile(input.files[0]);
                        }
                    };

                    input.onchange = () => {
                        if (input.files.length) {
                            handleFile(input.files[0]);
                        }
                    };

                    removeBtn.onclick = () => {
                        input.value = '';
                        preview.classList.remove('active');
                        errorMsg.style.display = 'none';
                    };

                    function handleFile(file) {
                        if (file.size > 10 * 1024 * 1024) { // 10MB
                            errorMsg.style.display = 'block';
                            input.value = ''; // Clear input
                            preview.classList.remove('active');
                        } else {
                            errorMsg.style.display = 'none';
                            fileInfo.textContent = file.name;
                            preview.classList.add('active');
                        }
                    }

                    fileWrapper.appendChild(zone);
                    fileWrapper.appendChild(preview);
                    fileWrapper.appendChild(errorMsg);
                    fileWrapper.appendChild(input);
                    wrapper.appendChild(fileWrapper);

                } else {
                    let input;
                    if (field.type === 'textarea') {
                        input = document.createElement('textarea');
                        input.rows = 3;
                    } else {
                        input = document.createElement('input');
                        input.type = field.type;
                        if (field.placeholder) input.placeholder = field.placeholder;
                    }

                    input.name = field.name;
                    input.className = 'form-control';
                    if (field.value) input.value = field.value;
                    wrapper.appendChild(input);
                }

                dynamicFields.appendChild(wrapper);
            });
        }

        function addListInput(container, name, value) {
            const row = document.createElement('div');
            row.style.display = 'flex';
            row.style.gap = '8px';
            row.style.marginBottom = '8px';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = name;
            input.className = 'form-control';
            input.value = value;
            input.placeholder = 'Info tambahan...';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-icon text-danger';
            removeBtn.textContent = 'X';
            removeBtn.onclick = () => row.remove();

            row.appendChild(input);
            row.appendChild(removeBtn);
            container.appendChild(row);
        }

        function addGroupInput(container, baseName, fields, index = null) {
            const idx = index !== null ? index : container.children.length;
            const row = document.createElement('div');
            row.style.border = '1px solid var(--border-color)';
            row.style.padding = '12px';
            row.style.borderRadius = '8px';
            row.style.marginBottom = '12px';
            row.style.background = '#f8fafc';
            row.style.position = 'relative';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-icon text-danger';
            removeBtn.style.position = 'absolute';
            removeBtn.style.top = '8px';
            removeBtn.style.right = '8px';
            removeBtn.textContent = 'Hapus';
            removeBtn.onclick = () => row.remove();

            if (container.children.length > 0) {
                row.appendChild(removeBtn);
            }

            fields.forEach(f => {
                const wrapper = document.createElement('div');
                wrapper.className = 'form-group';
                wrapper.style.marginBottom = '8px';

                const label = document.createElement('label');
                label.className = 'form-label';
                label.style.fontSize = '0.8rem';
                label.textContent = f.label;

                let input;
                if (f.type === 'textarea') {
                    input = document.createElement('textarea');
                    input.rows = 2;
                } else {
                    input = document.createElement('input');
                    input.type = f.type || 'text';
                }

                input.name = `${baseName}[${idx}][${f.name}]`;
                input.className = 'form-control';

                wrapper.appendChild(label);
                wrapper.appendChild(input);
                row.appendChild(wrapper);
            });

            container.appendChild(row);
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        // Close on click outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    </script>
@endpush