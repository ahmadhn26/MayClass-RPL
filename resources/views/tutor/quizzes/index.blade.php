@extends('tutor.layout')

@section('title', 'Manajemen Quiz - MayClass')

@push('styles')
    <style>
        :root {
            /* Menggunakan palet warna yang sama dengan Manajemen Materi untuk konsistensi */
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

        .header-title p {
            margin: 0;
            color: #64748b;
            font-size: 0.95rem;
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
            padding: 14px 50px 14px 20px;
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

        /* --- Quiz Grid --- */
        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .quiz-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            /* Padding disesuaikan karena tidak ada gambar */
            display: flex;
            gap: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .quiz-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: #cbd5e1;
        }

        /* CSS Thumbnail dihapus karena elemennya sudah tidak dipakai */

        .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
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
            flex-wrap: wrap;
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

        /* Purple Theme for Quiz Tags */
        .tag-subject {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .tag-level {
            background: #eff6ff;
            color: #2563eb;
        }

        .card-summary {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0 0 20px 0;
            /* Margin bawah ditambah */
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
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
            border-color: var(--primary-dark);
            /* Ubah warna hover jadi hijau juga agar konsisten */
            color: var(--primary-dark);
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
            background: #ffffff;
            border-radius: 20px;
            width: 90%;
            max-width: 650px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: #ffffff;
            z-index: 10;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
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

        .modal-body {
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-control {
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 0.95rem;
            transition: all 0.2s;
            width: 100%;
            background: white;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .btn-submit {
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
            margin-top: 8px;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 118, 110, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .quiz-card {
                flex-direction: column;
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
        <h1>Manajemen Quiz</h1>
        <div class="stats-badge">
            {{ $tableReady ? $quizzes->count() : 0 }} Quiz Aktif
        </div>
    </div>
    <a href="{{ route('tutor.quizzes.create') }}" class="btn-add">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Quiz
    </a>
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
        <strong>⚠ Database kuis belum siap</strong>
        <p style="margin: 8px 0;">Jalankan migrasi agar tutor dapat membuat kuis.</p>
        <code style="background: rgba(255,255,255,0.6); padding: 2px 6px; border-radius: 4px;">php artisan migrate</code>
    </div>
@elseif ($quizzes->isEmpty())
    <div class="empty-state">
        <div style="font-size: 3rem; margin-bottom: 16px;">📝</div>
        <strong style="display: block; font-size: 1.25rem; color: #1e293b; margin-bottom: 8px;">Belum ada quiz
            terdaftar</strong>
        <p>Buat quiz pertama Anda dan bagikan tautan evaluasi kepada siswa.</p>
    </div>
@else
    <div class="quiz-grid">
        @foreach ($quizzes as $quiz)
            <article class="quiz-card">
                {{-- BAGIAN GAMBAR SUDAH DIHAPUS DI SINI --}}

                <div class="card-content">
                    <h3 class="card-title" title="{{ $quiz->title }}">{{ $quiz->title }}</h3>

                    <div class="tags-row">
                        <span class="tag tag-subject">{{ $quiz->subject->name ?? 'Tanpa Mapel' }}</span>
                        <span class="tag tag-level">{{ $quiz->class_level ?? 'Semua Kelas' }}</span>
                    </div>

                    <p class="card-summary">{{ Str::limit($quiz->summary, 100) }}</p>

                    <div class="card-actions">
                        <a href="{{ route('tutor.quizzes.edit', $quiz) }}" class="action-btn btn-secondary">
                            Edit
                        </a>
                        @if ($quiz->link)
                            <a href="{{ $quiz->link }}" class="action-btn btn-outline" target="_blank" rel="noopener"
                                title="Buka link quiz">
                                Buka Quiz
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@endif

{{-- Modal: Tambah Quiz Baru --}}
<div id="createModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Tambah Quiz Baru</h2>
            <button type="button" onclick="closeModal()" class="btn-close">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('tutor.quizzes.store') }}" method="POST">
            @csrf

            <div class="modal-body">
                {{-- Hidden Fields --}}
                <input type="hidden" name="class_level" value="-">
                <input type="hidden" name="duration_label" value="-">
                <input type="hidden" name="question_count" value="1">

                {{-- Paket & Judul --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pilih Paket Belajar</label>
                        <select name="package_id" id="packageSelect" class="form-control" required>
                            <option value="">Pilih paket yang tersedia</option>
                            @foreach($packages ?? [] as $package)
                                <option value="{{ $package->id }}">{{ $package->detail_title ?? $package->name }} ({{ $package->level }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Judul Quiz</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Quiz Persamaan Linear"
                            required>
                    </div>
                </div>

                {{-- Mata Pelajaran --}}
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" id="subjectSelect" class="form-control" required disabled>
                        <option value="">Pilih paket terlebih dahulu</option>
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label class="form-label">Deskripsi Quiz</label>
                    <textarea name="summary" rows="3" class="form-control"
                        placeholder="Tuliskan deskripsi singkat quiz..." required></textarea>
                </div>

                {{-- Link Quiz --}}
                {{-- Link Quiz Dynamic --}}
                <div class="dynamic-group span-full">
                    <div class="dynamic-group__header">
                        <span>Link Quiz (Google Form / Lainnya)</span>
                        <button type="button" class="dynamic-add" data-add-link>+ Tambah Link</button>
                    </div>
                    <div class="dynamic-group__items" data-link-urls>
                        <div class="dynamic-item">
                            <div class="dynamic-item__row">
                                <input type="url" name="link_urls[]" placeholder="https://" required />
                            </div>
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    </div>
                    @error('link_urls.*') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn-submit">✓ Simpan Quiz</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        // Modal Logic
        const modal = document.getElementById('createModal');

        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal if clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        // Auto open modal if validation error exists
        @if($errors->any())
            openModal();
        @endif

        // --- AJAX Fetch Subjects (Global Scope) ---
        window.fetchSubjects = function(packageId) {
            console.log('[DEBUG] fetchSubjects called with packageId:', packageId);
            const subjectSelect = document.getElementById('subjectSelect');

            if (!packageId) {
                console.log('[DEBUG] No packageId provided, resetting dropdown');
                subjectSelect.innerHTML = '<option value="">-- Pilih Paket Dulu --</option>';
                subjectSelect.disabled = true;
                return;
            }

            console.log('[DEBUG] Setting loading state');
            subjectSelect.innerHTML = '<option>Loading...</option>';
            subjectSelect.disabled = true;

            // Use Laravel route helper for correct URL
            const url = "{{ route('tutor.packages.subjects', ':id') }}".replace(':id', packageId);
            console.log('[DEBUG] Fetching from URL:', url);

            fetch(url)
                .then(response => {
                    console.log('[DEBUG] Response received:', response.status, response.statusText);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('[DEBUG] Data received:', data);
                    subjectSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                    if (Array.isArray(data) && data.length > 0) {
                        console.log('[DEBUG] Adding', data.length, 'subjects to dropdown');
                        data.forEach(subject => {
                            subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name} (${subject.level})</option>`;
                        });
                        subjectSelect.disabled = false;
                        console.log('[DEBUG] Dropdown enabled with subjects');
                    } else {
                        console.log('[DEBUG] No subjects found in response');
                        subjectSelect.innerHTML = '<option value="">Tidak ada mapel tersedia</option>';
                    }
                })
                .catch(error => {
                    console.error('[ERROR] Fetch failed:', error);
                    subjectSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                });
        };

        // --- Dynamic Inputs Logic ---
        document.addEventListener('DOMContentLoaded', function () {
            console.log('[INIT] DOM Content Loaded');
            
            // Attach event listener to packageSelect
            const packageSelect = document.getElementById('packageSelect');
            if (packageSelect) {
                console.log('[INIT] packageSelect found, attaching change listener');
                packageSelect.addEventListener('change', function(e) {
                    console.log('[EVENT] Package changed to:', this.value);
                    fetchSubjects(this.value);
                });
            } else {
                console.error('[INIT] packageSelect NOT FOUND!');
            }
            
            // Logic for dynamic inputs only
            const linkContainer = document.querySelector('[data-link-urls]');
            const addLinkBtn = document.querySelector('[data-add-link]');

            // Template for new link row
            const createLinkRow = () => {
                const div = document.createElement('div');
                div.className = 'dynamic-item';
                div.innerHTML = `
                        <div class="dynamic-item__row">
                            <input type="url" name="link_urls[]" placeholder="https://" required />
                        </div>
                        <div class="dynamic-item__actions">
                            <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                        </div>
                    `;
                return div;
            };

            // Add new row
            if (addLinkBtn && linkContainer) {
                addLinkBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const newRow = createLinkRow();
                    linkContainer.appendChild(newRow);
                });
            }

            // Remove row (event delegation)
            if (linkContainer) {
                linkContainer.addEventListener('click', (e) => {
                    if (e.target.closest('[data-remove-row]')) {
                        e.preventDefault();
                        const row = e.target.closest('.dynamic-item');
                        if (linkContainer.children.length > 1) {
                            row.remove();
                        } else {
                            // If it's the last row, just clear the input
                            row.querySelector('input').value = '';
                        }
                    }
                });
            }
        });
    </script>
@endpush
@endsection