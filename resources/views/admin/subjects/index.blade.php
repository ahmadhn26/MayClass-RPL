@extends('admin.layout')

@section('title', 'Manajemen Mata Pelajaran - MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --primary-hover: #115e59;
            --bg-surface: #ffffff;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Header Section */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            background: var(--bg-surface);
            padding: 24px 32px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .header-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 4px 0;
        }

        .header-title p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 4px 6px -1px rgba(15, 118, 110, 0.2);
            cursor: pointer;
            border: none;
        }

        .btn-add:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: var(--bg-surface);
            padding: 20px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Filter Section */
        .filter-section {
            background: var(--bg-surface);
            padding: 20px 24px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .filter-group {
            display: flex;
            gap: 8px;
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-main);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .filter-btn:hover {
            background: #f8fafc;
            border-color: var(--primary);
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 8px 16px 8px 40px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        /* Table Card */
        .table-card {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .subject-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
        }

        .subject-table th {
            background: #f8fafc;
            text-align: left;
            padding: 16px 24px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 700;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }

        .subject-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        .subject-table tr:last-child td {
            border-bottom: none;
        }

        .subject-table tbody tr {
            transition: background 0.2s;
        }

        .subject-table tbody tr:hover {
            background: #f1f5f9;
        }

        .subject-name {
            font-weight: 700;
            color: var(--text-main);
            font-size: 1rem;
        }

        .subject-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .level-badge {
            display: inline-flex;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .level-sd {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .level-smp {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .level-sma {
            background: #fef3c7;
            color: #a16207;
            border: 1px solid #fde68a;
        }

        .status-badge {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-group {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            transition: all 0.2s;
            color: var(--text-muted);
            cursor: pointer;
            background: transparent;
        }

        .btn-icon:hover {
            background: #f1f5f9;
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .btn-icon.delete:hover {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
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

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
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
            padding: 32px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
        }

        .form-group label {
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
            background: #fff;
        }

        textarea.form-control {
            resize: vertical;
        }

        .helper-text {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            font-weight: 500;
        }

        .checkbox-label input {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
        }

        .modal-footer {
            padding: 24px 32px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: #f8fafc;
            border-radius: 0 0 20px 20px;
        }

        .btn-cancel {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--text-muted);
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-cancel:hover {
            color: var(--text-main);
            background: #f1f5f9;
        }

        .btn-submit {
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background: #3fa67e;
            /* Solid primary green - NO GRADIENT */
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(63, 166, 126, 0.3);
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: #2f8a67;
            /* Darker shade on hover */
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(63, 166, 126, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
            }

            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                width: 100%;
            }

            /* Force Stats Grid to Single Row */
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }

            .stat-card {
                padding: 12px;
                align-items: center;
                text-align: center;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            .stat-label {
                font-size: 0.7rem;
                white-space: nowrap;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-container">

        {{-- Header --}}
        <div class="page-header">
            <div class="header-title">
                <h2>Manajemen Mata Pelajaran</h2>
                <p>Kelola daftar mata pelajaran untuk setiap jenjang pendidikan.</p>
            </div>
            <button type="button" class="btn-add" onclick="openModal('addSubjectModal')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Mata Pelajaran
            </button>
        </div>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Mata Pelajaran</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Aktif</div>
                <div class="stat-value" style="color: #059669;">{{ $stats['active'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Nonaktif</div>
                <div class="stat-value" style="color: #dc2626;">{{ $stats['inactive'] }}</div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.subjects.index') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <a href="{{ route('admin.subjects.index', ['level' => 'all', 'status' => request('status'), 'q' => request('q')]) }}"
                            class="filter-btn {{ $filters['level'] === 'all' ? 'active' : '' }}">Semua Jenjang</a>
                        <a href="{{ route('admin.subjects.index', ['level' => 'SD', 'status' => request('status'), 'q' => request('q')]) }}"
                            class="filter-btn {{ $filters['level'] === 'SD' ? 'active' : '' }}">SD</a>
                        <a href="{{ route('admin.subjects.index', ['level' => 'SMP', 'status' => request('status'), 'q' => request('q')]) }}"
                            class="filter-btn {{ $filters['level'] === 'SMP' ? 'active' : '' }}">SMP</a>
                        <a href="{{ route('admin.subjects.index', ['level' => 'SMA', 'status' => request('status'), 'q' => request('q')]) }}"
                            class="filter-btn {{ $filters['level'] === 'SMA' ? 'active' : '' }}">SMA</a>
                    </div>

                    <div class="filter-group">
                        <a href="{{ route('admin.subjects.index', ['level' => request('level'), 'status' => 'all', 'q' => request('q')]) }}"
                            class="filter-btn {{ $filters['status'] === 'all' ? 'active' : '' }}">Semua Status</a>
                        <a href="{{ route('admin.subjects.index', ['level' => request('level'), 'status' => 'active', 'q' => request('q')]) }}"
                            class="filter-btn {{ $filters['status'] === 'active' ? 'active' : '' }}">Aktif</a>
                        <a href="{{ route('admin.subjects.index', ['level' => request('level'), 'status' => 'inactive', 'q' => request('q')]) }}"
                            class="filter-btn {{ $filters['status'] === 'inactive' ? 'active' : '' }}">Nonaktif</a>
                    </div>

                    <div class="search-box">
                        <svg class="search-icon" width="18" height="18" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="q" placeholder="Cari nama mata pelajaran..."
                            value="{{ $filters['query'] }}">
                        <input type="hidden" name="level" value="{{ $filters['level'] }}">
                        <input type="hidden" name="status" value="{{ $filters['status'] }}">
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="subject-table">
                    <thead>
                        <tr>
                            <th>Nama Mata Pelajaran</th>
                            <th>Jenjang</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr>
                                <td>
                                    <div class="subject-name">{{ $subject->name }}</div>
                                </td>
                                <td>
                                    <span
                                        class="level-badge level-{{ strtolower($subject->level) }}">{{ $subject->level }}</span>
                                </td>
                                <td>
                                    <div class="subject-desc">{{ $subject->description ?: '—' }}</div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $subject->is_active ? 'active' : 'inactive' }}">
                                        {{ $subject->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button type="button" class="btn-icon" title="Edit Mata Pelajaran"
                                            onclick="openEditSubjectModal({{ $subject->id }})">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <button type="button" class="btn-icon delete btn-delete"
                                            title="Nonaktifkan Mata Pelajaran" data-id="{{ $subject->id }}"
                                            data-name="{{ $subject->name }}"
                                            data-action="{{ route('admin.subjects.destroy', $subject) }}">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <svg style="width: 48px; height: 48px; margin-bottom: 16px; color: #cbd5e1;" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                        <p>Belum ada mata pelajaran yang tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ADD SUBJECT MODAL --}}
    <div id="addSubjectModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Mata Pelajaran</h2>
                <button type="button" class="btn-close" onclick="closeModal('addSubjectModal')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Mata Pelajaran *</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Matematika, Fisika"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Jenjang Pendidikan *</label>
                        <select name="level" class="form-control" required>
                            <option value="" disabled selected>Pilih jenjang</option>
                            @foreach ($levels as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Deskripsi singkat..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" checked>
                            Aktifkan mata pelajaran ini
                        </label>
                        <p class="helper-text" style="margin-left: 24px;">Mata pelajaran aktif dapat dipilih saat membuat
                            paket.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('addSubjectModal')">Batal</button>
                    <button type="submit" class="btn-submit">✓ Simpan Mata Pelajaran</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT SUBJECT MODAL --}}
    <div id="editSubjectModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Mata Pelajaran</h2>
                <button type="button" class="btn-close" onclick="closeEditSubjectModal()">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="editSubjectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Mata Pelajaran *</label>
                        <input type="text" id="edit_name" name="name" class="form-control"
                            placeholder="Contoh: Matematika, Fisika" required>
                    </div>

                    <div class="form-group">
                        <label>Jenjang Pendidikan *</label>
                        <select id="edit_level" name="level" class="form-control" required>
                            <option value="">Pilih jenjang</option>
                            @foreach ($levels as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi (Opsional)</label>
                        <textarea id="edit_description" name="description" class="form-control" rows="3"
                            placeholder="Deskripsi singkat..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditSubjectModal()">Batal</button>
                    <button type="submit" class="btn-submit">Perbarui Mata Pelajaran</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openModal(modalId) {
                document.getElementById(modalId).classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.remove('active');
                document.body.style.overflow = 'auto';
            }

            // Close modal on outside click
            window.onclick = function (event) {
                if (event.target.classList.contains('modal-overlay')) {
                    event.target.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }

            // Edit Subject Modal Functions
            async function openEditSubjectModal(subjectId) {
                try {
                    const response = await fetch(`/admin/subjects/${subjectId}/edit`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    // Set form action
                    document.getElementById('editSubjectForm').action = `/admin/subjects/${subjectId}`;

                    // Populate fields
                    document.getElementById('edit_name').value = data.name || '';
                    document.getElementById('edit_level').value = data.level || '';
                    document.getElementById('edit_description').value = data.description || '';

                    // Open modal
                    document.getElementById('editSubjectModal').classList.add('active');
                    document.body.style.overflow = 'hidden';

                } catch (error) {
                    console.error('Error loading subject data:', error);
                    alert('Gagal memuat data mata pelajaran. Silakan coba lagi.');
                }
            }

            function closeEditSubjectModal() {
                document.getElementById('editSubjectModal').classList.remove('active');
                document.body.style.overflow = 'auto';
            }

            // Auto open modal if validation errors exist
            @if($errors->any())
                openModal('addSubjectModal');
            @endif
        </script>
    @endpush
@endsection