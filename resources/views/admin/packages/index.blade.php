@extends('admin.layout')

@section('title', 'Manajemen Paket - MayClass')

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

        /* --- 1. HEADER SECTION --- */
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

        /* --- 2. TABLE CARD --- */
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

        .package-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
            min-width: 900px;
        }

        .package-table th {
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

        .package-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        .package-table tr:last-child td {
            border-bottom: none;
        }

        .package-table tbody tr {
            transition: background 0.2s;
        }

        .package-table tbody tr:hover {
            background: #f1f5f9;
        }

        /* Specific Column Styles */
        .pkg-name {
            font-weight: 700;
            color: var(--text-main);
            display: block;
            font-size: 1rem;
        }

        .pkg-price-label {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .pkg-level {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 6px;
            background: #f0f9ff;
            color: #0369a1;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #e0f2fe;
        }

        .pkg-grades {
            display: block;
            margin-top: 4px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .pkg-price {
            font-family: monospace;
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .tag-pill {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .tag-default {
            background: #f1f5f9;
            color: #64748b;
        }

        .tag-highlight {
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #ffedd5;
        }

        /* Orange for active tags */

        /* Actions */
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
            max-width: 900px;
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

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feature-item {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-remove-feature {
            padding: 8px 12px;
            border-radius: 8px;
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-add-feature {
            padding: 8px 16px;
            border-radius: 8px;
            background: #f0fdfa;
            color: #0f766e;
            border: 1px solid #ccfbf1;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 8px;
            width: fit-content;
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
            margin-top: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 118, 110, 0.4);
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

    {{-- Header --}}
    <div class="page-header">
        <div class="header-title">
            <h2>Manajemen Paket Belajar</h2>
            <p>Atur penawaran harga, jenjang pendidikan, dan detail paket untuk siswa.</p>
        </div>
        <button type="button" class="btn-add" onclick="openModal('addPackageModal')">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Paket Baru
        </button>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="package-table">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Jenjang & Kelas</th>
                        <th>Harga</th>
                        <th>Kuota</th>
                        <th>Mata Pelajaran</th>
                        <th>Tutor</th>
                        <th>Tag / Label</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                    <tr>
                        <td>
                            <span class="pkg-name">{{ $package->detail_title }}</span>
                            <span class="pkg-price-label">{{ $package->detail_price_label }}</span>
                        </td>
                        <td>
                            <span
                                class="pkg-level">{{ \App\Support\PackagePresenter::stageLabel($package->level) }}</span>
                            @if ($package->grade_range)
                                <span class="pkg-grades">{{ $package->grade_range }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="pkg-price">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            @php($quota = $package->quotaSnapshot())
                            @if ($quota['limit'] === null)
                                <span class="tag-pill tag-default">Tak terbatas</span>
                            @else
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <strong>{{ $quota['remaining'] }} / {{ $quota['limit'] }} kursi tersisa</strong>
                                    <small style="color: var(--text-muted);">
                                        Aktif: {{ $quota['active_enrollments'] }}, Checkout terkunci:
                                        {{ $quota['checkout_holds'] }}
                                    </small>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($package->subjects->isNotEmpty())
                                <div class="subject-pills">
                                    @foreach($package->subjects->take(3) as $subject)
                                        <span class="subject-pill">{{ $subject->name }}</span>
                                    @endforeach
                                    @if($package->subjects->count() > 3)
                                        <span class="subject-pill-more">+{{ $package->subjects->count() - 3 }} lainnya</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            @if($package->tutors->isNotEmpty())
                                <div class="subject-pills">
                                    @foreach($package->tutors->take(3) as $tutor)
                                        <span class="subject-pill"
                                            style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd;">{{ $tutor->name }}</span>
                                    @endforeach
                                    @if($package->tutors->count() > 3)
                                        <span class="subject-pill-more"
                                            style="background: #f1f5f9; color: #64748b;">+{{ $package->tutors->count() - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            @if($package->tag)
                                <span class="tag-pill tag-highlight">{{ $package->tag }}</span>
                            @else
                                <span class="tag-pill tag-default">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.packages.edit', $package) }}" class="btn-icon"
                                    title="Edit Paket">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>

                                <button type="button" class="btn-icon delete btn-delete" title="Hapus Paket"
                                    data-id="{{ $package->id }}" data-name="{{ $package->detail_title }}"
                                    data-action="{{ route('admin.packages.destroy', $package) }}">
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
                        <td colspan="8">
                            <div class="empty-state">
                                <svg style="width: 48px; height: 48px; margin-bottom: 16px; color: #cbd5e1;" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p>Belum ada paket belajar yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ADD PACKAGE MODAL --}}
<div id="addPackageModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Paket Belajar</h2>
            <button type="button" class="btn-close" onclick="closeModal('addPackageModal')">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Slug *</label>
                        <input type="text" name="slug" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jenjang Pendidikan *</label>
                        <select name="level" class="form-control" required>
                            <option value="" disabled selected>Pilih jenjang</option>
                            @foreach ($stages as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Rentang Kelas *</label>
                        <input type="text" name="grade_range" class="form-control" placeholder="Contoh: Kelas 10 - 12"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Tag (Opsional)</label>
                        <input type="text" name="tag" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Harga (Numerik) *</label>
                        <input type="number" name="price" class="form-control" min="0" step="1000" required>
                    </div>
                    <div class="form-group">
                        <label>Kuota Maksimum (Opsional)</label>
                        <input type="number" name="max_students" class="form-control" min="1"
                            placeholder="Kosongkan jika tak terbatas">
                    </div>
                    <div class="form-group">
                        <label>Label Harga Kartu *</label>
                        <input type="text" name="card_price_label" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Label Harga Detail *</label>
                        <input type="text" name="detail_price_label" class="form-control" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Judul Paket *</label>
                        <input type="text" name="detail_title" class="form-control" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Ringkasan *</label>
                        <textarea name="summary" class="form-control" rows="3" required></textarea>
                    </div>

                    {{-- Features --}}
                    <div class="form-group full-width">
                        <label>Fitur Pricing (Kartu Paket)</label>
                        <p class="helper-text">Tambahkan fitur yang akan muncul di kartu paket.</p>
                        <div class="feature-list" id="feature-list">
                            <div class="feature-item">
                                <input type="text" name="card_features[]" class="form-control"
                                    placeholder="Contoh: 6x kelas live per bulan">
                                <button type="button" class="btn-remove-feature"
                                    onclick="removeFeature(this)">Hapus</button>
                            </div>
                        </div>
                        <button type="button" class="btn-add-feature" onclick="addFeature()">+ Tambah Fitur</button>
                    </div>

                    {{-- Subjects --}}
                    <div class="form-group full-width">
                        <label>Mata Pelajaran yang Termasuk *</label>
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

                    {{-- Tutors --}}
                    <div class="form-group full-width">
                        <label>Tutor Pengampu (Opsional)</label>
                        <div class="subject-selection">
                            @if($tutors->isEmpty())
                                <p class="helper-text">Belum ada tutor aktif.</p>
                            @else
                                <div class="subject-checkboxes">
                                    @foreach($tutors as $tutor)
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="tutors[]" value="{{ $tutor->id }}">
                                            {{ $tutor->name }}
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('addPackageModal')">Batal</button>
                <button type="submit" class="btn-submit">✓ Simpan Paket</button>
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

        function addFeature() {
            const list = document.getElementById('feature-list');
            const item = document.createElement('div');
            item.className = 'feature-item';
            item.innerHTML = `
                            <input type="text" name="card_features[]" class="form-control" placeholder="Contoh: 6x kelas live per bulan" />
                            <button type="button" class="btn-remove-feature" onclick="removeFeature(this)">Hapus</button>
                        `;
            list.appendChild(item);
        }

        function removeFeature(button) {
            const list = document.getElementById('feature-list');
            if (list.children.length > 1) {
                button.parentElement.remove();
            } else {
                button.parentElement.querySelector('input').value = '';
            }
        }

        // Close modal on outside click
        window.onclick = function (event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        // Auto open modal if validation errors exist
        @if($errors->any())
            openModal('addPackageModal');
        @endif
    </script>
@endpush
@endsection