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
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
            cursor: pointer;
            font-family: inherit;
            font-size: 0.95rem;
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
            padding: 14px 50px 14px 20px;
            /* Space for icon on right */
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
            padding: 24px;
            /* Padding sedikit diperbesar karena tidak ada gambar */
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

        /* CSS Thumbnail dihapus karena elemennya sudah tidak dipakai, 
                               tapi layout card-content akan otomatis menyesuaikan */

        .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            /* Prevent flex text overflow */
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

        .tag-subject {
            background: #eff6ff;
            color: #2563eb;
        }

        .tag-level {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .card-summary {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0 0 20px 0;
            /* Margin bawah sedikit ditambah */
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

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

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

        /* --- MODAL STYLES --- */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.2s ease;
        }

        .modal-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 700px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 28px;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            border-radius: 20px 20px 0 0;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .btn-close {
            background: #f1f5f9;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #64748b;
        }

        .btn-close:hover {
            background: #e2e8f0;
            color: #1e293b;
            transform: rotate(90deg);
        }

        .modal-content form {
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #334155;
        }

        .form-control {
            padding: 12px 14px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s;
            font-family: inherit;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(63, 166, 126, 0.1);
        }

        .form-control:disabled {
            background: #f8fafc;
            color: #94a3b8;
            cursor: not-allowed;
        }

        /* Dynamic Lists */
        .dynamic-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .dynamic-item {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .dynamic-item input {
            flex: 1;
        }

        .btn-add-item {
            padding: 10px 16px;
            background: #f1f5f9;
            border: 1px dashed var(--border-color);
            border-radius: 8px;
            color: #475569;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            width: fit-content;
        }

        .btn-add-item:hover {
            background: #e2e8f0;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-remove-item {
            width: 32px;
            height: 32px;
            min-width: 32px;
            background: #fee2e2;
            border: none;
            border-radius: 6px;
            color: #dc2626;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-remove-item:hover {
            background: #fecaca;
            transform: scale(1.1);
        }

        .btn-submit {
            padding: 14px 24px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(63, 166, 126, 0.3);
            margin-top: 12px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(63, 166, 126, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* --- MODERN FILE UPLOAD --- */
        .file-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            background: #f8fafc;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .file-upload-area:hover {
            border-color: var(--primary-color);
            background: #ecfdf5;
        }

        .file-upload-area.dragover {
            border-color: var(--primary-color);
            background: #d1fae5;
            transform: scale(1.02);
        }

        .upload-placeholder {
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .upload-text {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .file-preview {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            background: white;
            border-radius: 10px;
            margin: 8px;
        }

        .file-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }

        .file-info {
            flex: 1;
            min-width: 0;
        }

        .file-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-bottom: 4px;
        }

        .file-size {
            font-size: 0.85rem;
            color: #64748b;
        }

        .file-remove {
            width: 32px;
            height: 32px;
            min-width: 32px;
            background: #fee2e2;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-remove:hover {
            background: #fecaca;
            transform: rotate(90deg) scale(1.1);
        }

        /* --- DIVIDER --- */
        .divider-container {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
        }

        .divider-text {
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 4px;
        }

        /* --- GOOGLE DRIVE INPUT --- */
        .gdrive-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .gdrive-icon {
            position: absolute;
            left: 14px;
            pointer-events: none;
            z-index: 1;
        }

        .gdrive-input {
            padding-left: 46px !important;
            padding-right: 42px !important;
            border-color: #cbd5e1;
        }

        .gdrive-input:focus {
            border-color: #4285f4 !important;
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1) !important;
        }

        .gdrive-input::placeholder {
            color: #94a3b8;
        }

        .clear-gdrive-btn {
            position: absolute;
            right: 10px;
            width: 28px;
            height: 28px;
            background: #f1f5f9;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            z-index: 1;
        }

        .clear-gdrive-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
            transform: rotate(90deg);
        }

        .gdrive-hint {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            color: #64748b;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 640px) {
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

            .form-row {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 0;
                max-height: 100vh;
                border-radius: 0;
            }

            .modal-header {
                border-radius: 0;
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
    <button type="button" onclick="openModal()" class="btn-add">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Materi
    </button>
</div>

{{-- Search Toolbar --}}
<div class="content-toolbar">
    <form method="GET" class="search-wrapper">
        <input type="search" name="q" class="search-input" value="{{ $search }}"
            placeholder="Cari judul, mata pelajaran, atau jenjang..." />
        <button type="submit" class="search-btn" title="Cari">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </form>
</div>

{{-- Content Area --}}
@if (!$tableReady)
    <div class="system-alert">
        <strong>⚠ Database materi belum siap</strong>
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
        {{-- BAGIAN GAMBAR SUDAH DIHAPUS DI SINI --}}

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
                    class="action-btn btn-outline" target="_blank" rel="noopener">
                    Preview
                </a>
                @endif
            </div>
        </div>
    </article>
    @endforeach
</div>
@endif

{{-- --- MODERN MODAL STRUCTURE --- --}}
<div id="createModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Tambah Materi Baru</h2>
            <button type="button" onclick="closeModal()" class="btn-close">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('tutor.materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Paket & Mapel --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Pilih Paket Belajar</label>
                    <select name="package_id" id="packageSelect" class="form-control" required
                        onchange="fetchSubjects(this.value)">
                        <option value="">-- Pilih Paket --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-level="{{ $package->level }}">{{ $package->name }}
                                ({{ $package->level }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" id="subjectSelect" class="form-control" required disabled>
                        <option value="">-- Pilih Paket Dulu --</option>
                    </select>
                </div>
            </div>

            {{-- Detail Materi --}}
            <div class="form-group">
                <label class="form-label">Judul Materi</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Aljabar Dasar" required>
            </div>

            {{-- Hidden Level (Auto-filled from package) --}}
            <input type="hidden" name="level" id="hiddenLevel" required>

            {{-- Modern File Upload --}}
            <div class="form-group">
                <label class="form-label">Upload File Materi <small style="color: #64748b;">(PDF, PPT, DOC - Max
                        10MB)</small></label>
                <div class="file-upload-area" id="fileUploadArea">
                    <input type="file" name="attachment" id="fileInput" accept=".pdf,.ppt,.pptx,.doc,.docx"
                        style="display: none;">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            style="color: #94a3b8; margin-bottom: 12px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <div class="upload-text">
                            <strong style="color: #334155;">Klik untuk upload</strong>
                            <span style="color: #64748b; font-size: 0.875rem; margin-top: 4px; display: block;">atau
                                drag & drop file disini</span>
                        </div>
                    </div>
                    <div class="file-preview" id="filePreview" style="display: none;">
                        <div class="file-icon">
                            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="file-info">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>
                        <button type="button" class="file-remove" id="fileRemove">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Divider with "OR" --}}
            <div class="divider-container">
                <div class="divider-line"></div>
                <span class="divider-text">atau</span>
                <div class="divider-line"></div>
            </div>

            {{-- Google Drive Link Input --}}
            <div class="form-group">
                <label class="form-label">
                    Link Google Drive
                    <small style="color: #64748b; font-weight: 500;">(Opsional - jika tidak upload file)</small>
                </label>
                <div class="gdrive-input-wrapper">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" class="gdrive-icon">
                        <path
                            d="M12.01 2.011c1.74 0 3.28.67 4.44 1.77l-1.79 1.79c-.71-.71-1.69-1.14-2.65-1.14-2.06 0-3.73 1.67-3.73 3.73 0 .96.43 1.94 1.14 2.65l-1.79 1.79C6.68 10.62 6 9.08 6 7.34c0-3.31 2.69-6 6.01-6zm0 20c-3.32 0-6.01-2.69-6.01-6 0-1.74.68-3.28 1.83-4.43l1.79 1.79c-.71.71-1.14 1.69-1.14 2.65 0 2.06 1.67 3.73 3.73 3.73.96 0 1.94-.43 2.65-1.14l1.79 1.79c-1.15 1.15-2.69 1.83-4.43 1.83z"
                            fill="#4285f4" />
                        <path d="M21.99 13H14.4l3.8-6.6h7.59c.33 0 .6.27.6.6v5.4c0 .33-.27.6-.6.6z" fill="#ea4335" />
                        <path d="M14.4 13l-7.6 13.18c-.16.28-.52.37-.79.21-.28-.16-.37-.52-.21-.79L13.4 12h1z"
                            fill="#34a853" />
                    </svg>
                    <input type="url" name="gdrive_link" id="gdriveInput" class="form-control gdrive-input"
                        placeholder="https://drive.google.com/file/d/...">
                    <button type="button" id="clearGdriveBtn" class="clear-gdrive-btn" style="display: none;"
                        title="Hapus link">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <small class="gdrive-hint">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="display: inline-block; vertical-align: middle; color: #64748b;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pastikan file dapat diakses oleh siapa saja dengan link
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">Ringkasan Materi</label>
                <textarea name="summary" rows="3" class="form-control" placeholder="Deskripsi singkat materi..."
                    required></textarea>
            </div>

            {{-- Tujuan Pembelajaran Dinamis --}}
            <div class="form-group">
                <label class="form-label">Tujuan Pembelajaran</label>
                <ul id="objectivesList" class="dynamic-list">
                    <li class="dynamic-item">
                        <input type="text" name="objectives[]" class="form-control"
                            placeholder="Contoh: Memahami variabel">
                    </li>
                </ul>
                <button type="button" class="btn-add-item" onclick="addObjective()">+ Tambah Tujuan</button>
            </div>

            {{-- Bab Materi Dinamis --}}
            <div class="form-group">
                <label class="form-label">Bab / Sub-Materi</label>
                <div id="chaptersList" class="dynamic-list">
                    <div class="dynamic-item">
                        <input type="text" name="chapters[0][title]" class="form-control" placeholder="Judul Bab">
                        <input type="text" name="chapters[0][description]" class="form-control"
                            placeholder="Deskripsi (opsional)">
                    </div>
                </div>
                <button type="button" class="btn-add-item" onclick="addChapter()">+ Tambah Bab</button>
            </div>

            <button type="submit" class="btn-submit">Simpan Materi</button>
        </form>
    </div>
</div>

<script>
    // Modal Logic
    const modal = document.getElementById('createModal');

    function openModal() {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling background
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close modal if clicking outside content
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Auto open modal if validation error exists (Laravel)
    @if($errors->any())
        openModal();
    @endif

    // ========== AUTO-FILL LEVEL FROM PACKAGE ==========
    const packageSelect = document.getElementById('packageSelect');
    const hiddenLevel = document.getElementById('hiddenLevel');

    packageSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const level = selectedOption.getAttribute('data-level') || '';
        hiddenLevel.value = level;
    });

    // ========== MODERN FILE UPLOAD HANDLER ==========
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('fileInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileRemove = document.getElementById('fileRemove');

    // Click to upload
    fileUploadArea.addEventListener('click', (e) => {
        if (e.target.closest('.file-remove')) return; // Don't trigger if clicking remove button
        fileInput.click();
    });

    // Handle file selection
    fileInput.addEventListener('change', (e) => {
        handleFile(e.target.files[0]);
    });

    // Drag and drop handlers
    fileUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', () => {
        fileUploadArea.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
        const droppedFile = e.dataTransfer.files[0];
        if (droppedFile) {
            fileInput.files = e.dataTransfer.files; // Assign to input
            handleFile(droppedFile);
        }
    });

    // Handle file display
    function handleFile(file) {
        if (!file) return;

        // Validate file type
        const allowedTypes = ['.pdf', '.ppt', '.pptx', '.doc', '.docx'];
        const fileExt = '.' + file.name.split('.').pop().toLowerCase();
        if (!allowedTypes.includes(fileExt)) {
            alert('Format file tidak didukung. Hanya PDF, PPT, dan DOC yang diperbolehkan.');
            fileInput.value = '';
            return;
        }

        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB.');
            fileInput.value = '';
            return;
        }

        // Show preview
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        uploadPlaceholder.style.display = 'none';
        filePreview.style.display = 'flex';
    }

    // Remove file
    fileRemove.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent triggering file upload
        fileInput.value = '';
        uploadPlaceholder.style.display = 'flex';
        filePreview.style.display = 'none';
    });

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // ========== GOOGLE DRIVE LINK HANDLER ==========
    const gdriveInput = document.getElementById('gdriveInput');
    const clearGdriveBtn = document.getElementById('clearGdriveBtn');

    // Show/hide clear button based on input
    gdriveInput.addEventListener('input', function () {
        if (this.value.trim()) {
            clearGdriveBtn.style.display = 'flex';
        } else {
            clearGdriveBtn.style.display = 'none';
        }
    });

    // Clear button click
    clearGdriveBtn.addEventListener('click', function () {
        gdriveInput.value = '';
        clearGdriveBtn.style.display = 'none';
        gdriveInput.focus();
    });

    // AJAX Fetch Subjects
    function fetchSubjects(packageId) {
        const subjectSelect = document.getElementById('subjectSelect');

        if (!packageId) {
            subjectSelect.innerHTML = '<option value="">-- Pilih Paket Dulu --</option>';
            subjectSelect.disabled = true;
            return;
        }

        subjectSelect.innerHTML = '<option>Loading...</option>';
        subjectSelect.disabled = true;

        fetch(`/tutor/packages/${packageId}/subjects`)
            .then(response => response.json())
            .then(data => {
                subjectSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                data.forEach(subject => {
                    subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name} (${subject.level})</option>`;
                });
                subjectSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                subjectSelect.innerHTML = '<option value="">Gagal memuat</option>';
            });
    }

    // Dynamic Objectives
    function addObjective() {
        const list = document.getElementById('objectivesList');
        const li = document.createElement('li');
        li.className = 'dynamic-item';
        li.innerHTML = `
                <input type="text" name="objectives[]" class="form-control" placeholder="Tujuan lainnya...">
                <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
            `;
        list.appendChild(li);
    }

    // Dynamic Chapters
    let chapterIndex = 1;
    function addChapter() {
        const list = document.getElementById('chaptersList');
        const div = document.createElement('div');
        div.className = 'dynamic-item';
        div.innerHTML = `
                <input type="text" name="chapters[${chapterIndex}][title]" class="form-control" placeholder="Judul Bab">
                <input type="text" name="chapters[${chapterIndex}][description]" class="form-control" placeholder="Deskripsi">
                <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
            `;
        list.appendChild(div);
        chapterIndex++;
    }
</script>
@endsection