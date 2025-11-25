@extends('admin.layout')

@section('title', 'Manajemen Tentor - MayClass')

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
            --radius: 12px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* --- 1. HEADER CARD (Box Putih Rapi) --- */
        .header-card {
            background: var(--bg-surface);
            padding: 24px 32px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-content h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 4px;
        }

        .header-content p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.95rem;
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
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            border: none;
        }

        .btn-add:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* --- 2. STATS CARDS --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: var(--bg-surface);
            padding: 20px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 4px;
            line-height: 1;
        }

        /* --- 3. FILTERS & SEARCH --- */
        .filter-bar {
            background: var(--bg-surface);
            padding: 16px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-main);
            background: var(--bg-body);
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .filter-select {
            padding: 9px 14px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-main);
            background-color: #fff;
            cursor: pointer;
        }

        /* --- 4. TABLE (Fixed Alignment) --- */
        .table-card {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .tentor-table {
            width: 100%;
            border-collapse: separate;
            /* Penting untuk border-radius header */
            border-spacing: 0;
            font-size: 0.92rem;
            min-width: 1000px;
        }

        .tentor-table th {
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

        /* ALIGNMENT FIX: Header kolom terakhir rata kanan */
        .tentor-table th:last-child {
            text-align: right;
            padding-right: 24px;
        }

        .tentor-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        /* ALIGNMENT FIX: Isi kolom terakhir rata kanan */
        .tentor-table td:last-child {
            text-align: right;
            padding-right: 24px;
        }

        .tentor-table tr:last-child td {
            border-bottom: none;
        }

        .tentor-table tbody tr {
            transition: background 0.2s;
        }

        .tentor-table tbody tr:hover {
            background: #fcfcfc;
        }

        /* Column Styles */
        .profile-col {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border-color);
            background: #f1f5f9;
        }

        .user-info h4 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .user-info span {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            font-size: 0.85rem;
        }

        .contact-info span {
            color: var(--text-main);
        }

        .contact-info small {
            color: var(--text-muted);
        }

        .spec-badge {
            display: inline-block;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active {
            background: #dcfce7;
            color: #15803d;
        }

        .status-inactive {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* --- Action Buttons (Text Based & Aligned) --- */
        .action-group {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            /* Pastikan tombol menempel ke kanan */
            align-items: center;
        }

        .btn-action {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
            cursor: pointer;
            text-align: center;
        }

        /* Tombol Edit */
        .btn-edit {
            background: #f0f9ff;
            color: #0369a1;
            border-color: #bae6fd;
        }

        .btn-edit:hover {
            background: #e0f2fe;
            color: #0284c7;
            transform: translateY(-1px);
        }

        /* Tombol Hapus */
        .btn-delete {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .btn-delete:hover {
            background: #fee2e2;
            color: #dc2626;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .subject-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        .subject-pill {
            display: inline-flex;
            padding: 3px 10px;
            border-radius: 99px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid #bae6fd;
        }

        .subject-pill-more {
            display: inline-flex;
            padding: 3px 10px;
            border-radius: 99px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .text-muted {
            color: var(--text-muted);
            font-style: italic;
        }

        /* --- MODAL STYLES --- */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: #ffffff;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
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
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .btn-close:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        .modal-body {
            padding: 32px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-control {
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .helper-text {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
        }

        .avatar-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            background: #f1f5f9;
            border: 1px solid var(--border-color);
        }

        .avatar-upload {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-upload {
            padding: 8px 16px;
            background: #f1f5f9;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-upload:hover {
            background: #e2e8f0;
        }

        .subject-selection {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 8px;
        }

        .subject-group h4 {
            font-size: 0.9rem;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: var(--text-main);
        }

        .subject-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .checkbox-label:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
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
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            color: white;
            background: var(--primary);
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        @media (max-width: 768px) {
            .header-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-container">

        {{-- 1. HEADER CARD --}}
        <div class="header-card">
            <div class="header-content">
                <h1>Manajemen Tentor</h1>
                <p>Kelola profil, spesialisasi, dan status aktif pengajar di MayClass.</p>
            </div>
            <button type="button" class="btn-add" onclick="openModal('addTentorModal')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Tentor
            </button>
        </div>

        {{-- 2. STATS --}}
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Total Tentor</span>
                <span class="stat-value">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Aktif</span>
                <span class="stat-value" style="color: #0f766e;">{{ number_format($stats['active']) }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Nonaktif</span>
                <span class="stat-value" style="color: #b91c1c;">{{ number_format($stats['inactive']) }}</span>
            </div>
        </div>

        {{-- 3. FILTER & SEARCH --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('admin.tentors.index') }}" class="search-box">
                <span class="search-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="q" value="{{ $filters['query'] }}" placeholder="Cari nama atau email tentor...">
                <input type="hidden" name="status" value="{{ $filters['status'] }}">
            </form>

            <form method="GET" action="{{ route('admin.tentors.index') }}" class="filter-group">
                <input type="hidden" name="q" value="{{ $filters['query'] }}">
                <select name="status" onchange="this.form.submit()" class="filter-select">
                    <option value="all" @selected($filters['status'] === 'all')>Semua Status</option>
                    <option value="active" @selected($filters['status'] === 'active')>Aktif</option>
                    <option value="inactive" @selected($filters['status'] === 'inactive')>Nonaktif</option>
                </select>
            </form>
        </div>

        {{-- 4. TABLE --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="tentor-table">
                    <thead>
                        <tr>
                            <th>Profil Tentor</th>
                            <th>Kontak</th>
                            <th>Keahlian Mengajar</th>
                            <th>Spesialisasi & Pendidikan</th>
                            <th>Pengalaman</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tentors as $tentor)
                            <tr>
                                <td>
                                    <div class="profile-col">
                                        <img src="{{ $tentor['avatar'] }}" alt="Avatar" class="avatar">
                                        <div class="user-info">
                                            <h4>{{ $tentor['name'] }}</h4>
                                            <span>{{ $tentor['username'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <span>{{ $tentor['email'] }}</span>
                                        <small>{{ $tentor['phone'] ?: '-' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($tentor['subjects']->isNotEmpty())
                                        <div class="subject-pills">
                                            @foreach($tentor['subjects']->take(3) as $subject)
                                                <span class="subject-pill">{{ $subject->name }}</span>
                                            @endforeach
                                            @if($tentor['subjects']->count() > 3)
                                                <span class="subject-pill-more">+{{ $tentor['subjects']->count() - 3 }} lainnya</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <span class="spec-badge">{{ $tentor['specializations'] ?? 'Umum' }}</span>
                                        <small style="color: var(--text-muted);">{{ $tentor['education'] ?? '-' }}</small>
                                    </div>
                                </td>
                                <td>{{ $tentor['experience_years'] }} Tahun</td>
                                <td>
                                    <span class="status-pill {{ $tentor['is_active'] ? 'status-active' : 'status-inactive' }}">
                                        {{ $tentor['is_active'] ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('admin.tentors.edit', $tentor['id']) }}" class="btn-action btn-edit">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.tentors.destroy', $tentor['id']) }}"
                                            onsubmit="return confirm('Hapus tentor ini secara permanen?');"
                                            style="display: inline-block; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <p>Belum ada data tentor yang sesuai dengan pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ADD TENTOR MODAL --}}
    <div id="addTentorModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Tentor Baru</h2>
                <button type="button" class="btn-close" onclick="closeModal('addTentorModal')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.tentors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Avatar Upload --}}
                    <div class="form-group full-width" style="margin-bottom: 24px;">
                        <label>Foto Profil</label>
                        <div class="avatar-upload">
                            <img src="{{ asset('images/avatar-placeholder.svg') }}" alt="Preview" class="avatar-preview"
                                id="avatarPreview">
                            <div>
                                <label for="avatarInput" class="btn-upload">Pilih Foto</label>
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" hidden
                                    onchange="previewImage(this)">
                                <p class="helper-text" style="margin-top: 8px;">Format JPG/PNG, maks 5MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid">
                        {{-- Personal Info --}}
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Username *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        {{-- Professional Info --}}
                        <div class="form-group">
                            <label>Spesialisasi (Mapel) *</label>
                            <input type="text" name="specializations" class="form-control"
                                placeholder="Contoh: Matematika SMA" required>
                        </div>
                        <div class="form-group">
                            <label>Pendidikan Terakhir</label>
                            <input type="text" name="education" class="form-control"
                                placeholder="Contoh: S1 Pendidikan Matematika">
                        </div>
                        <div class="form-group">
                            <label>Pengalaman (Tahun)</label>
                            <input type="number" name="experience_years" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label>Headline Profil</label>
                            <input type="text" name="headline" class="form-control"
                                placeholder="Contoh: Tentor Berpengalaman">
                        </div>

                        <div class="form-group full-width">
                            <label>Bio Singkat</label>
                            <textarea name="bio" class="form-control" rows="3"
                                placeholder="Ceritakan pengalaman mengajar..."></textarea>
                        </div>

                        {{-- Security --}}
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        {{-- Subjects --}}
                        <div class="form-group full-width">
                            <label>Mata Pelajaran yang Diampu *</label>
                            <div class="subject-selection">
                                @foreach(['SD', 'SMP', 'SMA'] as $level)
                                    @if($subjectsByLevel[$level]->isNotEmpty())
                                        <div class="subject-group">
                                            <h4>{{ $level }}</h4>
                                            <div class="subject-checkboxes">
                                                @foreach($subjectsByLevel[$level] as $subject)
                                                    <label class="checkbox-label">
                                                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}">
                                                        {{ $subject->name }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label class="checkbox-label" style="width: fit-content;">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" checked>
                                Aktifkan Akun Tentor
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('addTentorModal')">Batal</button>
                    <button type="submit" class="btn-submit">Simpan Tentor</button>
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

            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('avatarPreview').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Close modal on outside click
            window.onclick = function (event) {
                if (event.target.classList.contains('modal-overlay')) {
                    event.target.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }
        </script>
    @endpush
@endsection