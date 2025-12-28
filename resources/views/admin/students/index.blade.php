@extends('admin.layout')

@section('title', 'Manajemen Siswa - MayClass')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

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

        /* SweetAlert z-index fix - make it appear above modal */
        .swal2-container {
            z-index: 99999 !important;
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

        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* Search Box */
        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            /* Space for icon */
            border-radius: 99px;
            border: 1px solid var(--border-color);
            background: var(--bg-body);
            color: var(--text-main);
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
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

        .students-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
            min-width: 900px;
        }

        .students-table th {
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

        .students-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        .students-table tr:last-child td {
            border-bottom: none;
        }

        .students-table tbody tr {
            transition: background 0.2s;
            cursor: pointer;
        }

        .students-table tbody tr:hover {
            background: #f1f5f9;
        }

        /* Column Specific Styles */
        .student-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #4338ca;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .student-info {
            display: flex;
            flex-direction: column;
        }

        .student-name {
            font-weight: 600;
            color: var(--text-main);
        }

        .student-email {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .student-id-badge {
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.85rem;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        /* Status Pills */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-pill[data-state='active'] {
            background: #dcfce7;
            color: #15803d;
        }

        .status-pill[data-state='inactive'] {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-pill[data-state='pending'] {
            background: #fef9c3;
            color: #a16207;
        }

        /* Action Button */
        .btn-detail {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .btn-detail:hover {
            background: #f0fdfa;
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: #ef4444;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }

        /* Reset Password Button */
        .btn-reset-password {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
        }

        .btn-reset-password:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(249, 115, 22, 0.45);
        }

        .btn-reset-password:active {
            transform: translateY(0);
        }

        .btn-reset-password:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-reset-password svg {
            flex-shrink: 0;
        }

        /* Password Alert */
        .password-alert {
            display: none;
            background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%);
            border: 1px solid #fbbf24;
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 16px;
        }

        .password-alert.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .password-alert-header {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #92400e;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .password-value {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #fcd34d;
        }

        .password-value code {
            font-family: 'Courier New', monospace;
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: 1px;
            flex: 1;
        }

        .btn-copy-password {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: #0f766e;
            color: white;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy-password:hover {
            background: #115e59;
        }

        .btn-copy-password.copied {
            background: #10b981;
        }

        .password-alert-note {
            margin-top: 12px;
            font-size: 0.8rem;
            color: #78350f;
        }

        /* Password Actions Section */
        .password-section {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .password-section h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 16px 0;
        }

        /* WhatsApp Counseling Button */
        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #25D366;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(37, 211, 102, 0.3);
        }

        .btn-whatsapp:hover {
            background: #128C7E;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .btn-whatsapp svg {
            flex-shrink: 0;
        }

        .btn-whatsapp span {
            white-space: nowrap;
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
            border-radius: 20px 20px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .btn-close-modal {
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

        .btn-close-modal:hover {
            background: #e2e8f0;
            color: #1e293b;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 32px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .detail-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border-color);
        }

        .detail-card.full-width {
            grid-column: span 2;
        }

        .detail-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 1rem;
            color: var(--text-main);
            font-weight: 600;
        }

        .timeline-section {
            margin-top: 32px;
        }

        .timeline-section h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 16px 0;
        }

        .timeline-item {
            padding: 16px 20px;
            border-radius: 10px;
            background: white;
            border: 1px solid var(--border-color);
            margin-bottom: 12px;
        }

        .timeline-item-header {
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .timeline-meta {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-card.full-width {
                grid-column: span 1;
            }

            .modal-content {
                width: 100%;
                max-height: 100vh;
                border-radius: 0;
            }

            .modal-header {
                border-radius: 0;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box {
                width: 100%;
            }

            /* Show only WhatsApp icon on mobile */
            .btn-whatsapp {
                padding: 8px 10px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .btn-whatsapp span {
                display: none;
            }

            .btn-whatsapp {
                padding: 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-container">

        {{-- Header & Filter --}}
        <div class="page-header">
            <div class="header-title">
                <h2>Manajemen Siswa</h2>
                <p>Kelola data, status paket belajar, dan informasi akun siswa.</p>
            </div>
            <div class="header-actions">
                <form class="search-box" action="#" method="GET"> {{-- Tambahkan route search jika ada --}}
                    <span class="search-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="q" id="search-input" placeholder="Cari nama atau email siswa..."
                        value="{{ request('q') }}" autocomplete="off">
                </form>
            </div>
        </div>

        {{-- Table Data --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Profil Siswa</th>
                            <th>ID Siswa</th>
                            <th>Paket Aktif</th>
                            <th>Status Paket</th>
                            <th>Masa Berlaku</th>
                            <th style="text-align: center;">Konseling</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body">
                        @include('admin.students._table_rows')
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- STUDENT DETAIL MODAL --}}
    <div id="studentDetailModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Siswa</h2>
                <button type="button" class="btn-close-modal" onclick="closeDetailModal()">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="modal-body">
                {{-- Student Info Grid --}}
                <div class="detail-grid">
                    <div class="detail-card full-width"
                        style="background: linear-gradient(135deg, rgba(31, 209, 161, 0.08), rgba(84, 101, 255, 0.08));">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                            <div class="student-avatar" id="modal_avatar"
                                style="width: 64px; height: 64px; font-size: 1.5rem;"></div>
                            <div>
                                <h3 id="modal_name" style="margin: 0 0 4px 0; font-size: 1.25rem;"></h3>
                                <p id="modal_email" style="margin: 0; color: var(--text-muted); font-size: 0.95rem;"></p>
                                <p id="modal_student_id"
                                    style="margin: 4px 0 0 0; color: var(--text-muted); font-size: 0.85rem;"></p>
                            </div>
                        </div>
                        <div id="modal_package_summary"></div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Username</div>
                        <div class="detail-value" id="modal_username">-</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Nomor Telepon</div>
                        <div class="detail-value" id="modal_phone">-</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Orang Tua/Wali</div>
                        <div class="detail-value" id="modal_parent_name">-</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Jenis Kelamin</div>
                        <div class="detail-value" id="modal_gender">-</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Alamat</div>
                        <div class="detail-value" id="modal_address">-</div>
                    </div>
                </div>

                {{-- Password Section --}}
                <div class="password-section">
                    <h3>Manajemen Password</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">Generate password baru jika
                        siswa lupa password atau membutuhkan reset akses login.</p>
                    <button type="button" class="btn-reset-password" id="btn_generate_password"
                        onclick="generateNewPassword()">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                        <span>Generate Password Baru</span>
                    </button>

                    <div class="password-alert" id="password_alert">
                        <div class="password-alert-header">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Password Baru Berhasil Dibuat!
                        </div>
                        <div class="password-value">
                            <code id="generated_password_text">-</code>
                            <button type="button" class="btn-copy-password" onclick="copyPassword()">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span id="copy_btn_text">Salin</span>
                            </button>
                        </div>
                        <p class="password-alert-note">⚠️ Simpan dan bagikan password ini ke siswa melalui kanal resmi.
                            Password tidak akan ditampilkan lagi setelah modal ditutup.</p>
                    </div>
                </div>

                {{-- Timeline Section --}}
                <div class="timeline-section">
                    <h3>Riwayat Paket & Pembayaran</h3>
                    <div id="modal_timeline">
                        <p style="color: var(--text-muted); text-align: center; padding: 20px;">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Form for Deletion --}}
    <form id="delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Function to re-bind all event listeners (called on load and after AJAX)
                const reattachEventListeners = () => {
                    // 1. DELETE BUTTONS
                    const deleteButtons = document.querySelectorAll('.btn-delete');
                    const deleteForm = document.getElementById('delete-form');

                    deleteButtons.forEach(button => {
                        button.onclick = function (e) {
                            e.stopPropagation(); // Prevent row click
                            const studentId = this.dataset.id;
                            const studentName = this.dataset.name;
                            const isActive = this.dataset.active === 'active';

                            if (isActive) {
                                // Block Deletion
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menghapus',
                                    text: `Tidak bisa menghapus siswa "${studentName}" karena masih memiliki paket belajar aktif.`,
                                    confirmButtonColor: '#0f766e',
                                    confirmButtonText: 'Mengerti'
                                });
                            } else {
                                // Confirm Deletion
                                Swal.fire({
                                    title: 'Apakah Anda yakin?',
                                    text: `Data siswa "${studentName}" akan dihapus secara permanen.`,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#ef4444',
                                    cancelButtonColor: '#64748b',
                                    confirmButtonText: 'Ya, Hapus!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        deleteForm.action = `/admin/students/${studentId}`;
                                        deleteForm.submit();
                                    }
                                });
                            }
                        };
                    });

                    // 2. TOGGLE STATUS BUTTONS
                    const toggleButtons = document.querySelectorAll('.btn-toggle-status');
                    toggleButtons.forEach(button => {
                        button.onclick = function (e) {
                            e.stopPropagation(); // Prevent row click
                            const studentId = this.dataset.id;
                            const studentName = this.dataset.name;
                            const isActive = this.dataset.active === '1';
                            const action = isActive ? 'menonaktifkan' : 'mengaktifkan';
                            const actionTitle = isActive ? 'Nonaktifkan' : 'Aktifkan';

                            Swal.fire({
                                title: `${actionTitle} Paket?`,
                                html: `Apakah Anda yakin ingin ${action} paket siswa <strong>"${studentName}"</strong>?<br><br>${isActive ? '⚠️ Siswa tidak dapat mengakses materi, kuis, dan jadwal jika paket dinonaktifkan.' : '✅ Siswa akan dapat mengakses kembali materi, kuis, dan jadwal.'}`,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: isActive ? '#f59e0b' : '#10b981',
                                cancelButtonColor: '#64748b',
                                confirmButtonText: `Ya, ${actionTitle}!`,
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        title: 'Memproses...',
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });

                                    fetch(`/admin/students/${studentId}/toggle-status`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        }
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                button.dataset.active = data.is_active ? '1' : '0';
                                                if (data.is_active) {
                                                    button.style.background = '#fef3c7';
                                                    button.style.color = '#d97706';
                                                    button.style.borderColor = '#fbbf24';
                                                } else {
                                                    button.style.background = '#d1fae5';
                                                    button.style.color = '#059669';
                                                    button.style.borderColor = '#34d399';
                                                }
                                                button.title = data.is_active ? 'Nonaktifkan Paket' : 'Aktifkan Paket';
                                                button.innerHTML = data.is_active
                                                    ? '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 715.636 5.636m12.728 12.728L5.636 5.636"></path></svg><span>Nonaktifkan</span>'
                                                    : '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Aktifkan</span>';

                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil!',
                                                    text: data.message,
                                                    timer: 2000,
                                                    showConfirmButton: false
                                                });

                                                // Optional: Update pill elsewhere if needed, but for now button is enough
                                                // or reload partial for full consistency (but that resets list)
                                            } else {
                                                throw new Error(data.error || 'Terjadi kesalahan');
                                            }
                                        })
                                        .catch(error => {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Gagal',
                                                text: error.message || 'Terjadi kesalahan saat mengubah status siswa.',
                                            });
                                        });
                                }
                            });
                        };
                    });

                    // 3. ROW CLICKS (Detail Modal)
                    const tableRows = document.querySelectorAll('.students-table tbody tr[data-student-id]');
                    tableRows.forEach(row => {
                        row.onclick = function (e) {
                            // Check if click was on a button or link (already handled by propagation stop, but safety check)
                            if (e.target.closest('button') || e.target.closest('a')) return;

                            const studentId = this.dataset.studentId;
                            if (studentId) {
                                openDetailModal(studentId);
                            }
                        };
                    });
                };

                // Call once on load
                reattachEventListeners();

                // Live Search Logic
                const searchInput = document.getElementById('search-input');
                const tableBody = document.getElementById('students-table-body');
                let timeout = null;

                if (searchInput) {
                    searchInput.addEventListener('input', function () {
                        const query = this.value;
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            fetch(`{{ route('admin.students.index') }}?q=${query}`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            })
                                .then(response => response.text())
                                .then(html => {
                                    tableBody.innerHTML = html;
                                    reattachEventListeners(); // IMPORTANT: Re-bind listeners to new rows
                                })
                                .catch(error => console.error('Error:', error));
                        }, 300);
                    });
                }

                @if(session('status'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: "{{ session('status') }}",
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: "{{ session('error') }}",
                    });
                @endif
                            });

            // ========== MODAL FUNCTIONS ==========
            let currentStudentId = null;

            function openDetailModal(studentId) {
                currentStudentId = studentId;
                const modal = document.getElementById('studentDetailModal');
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';

                // Reset password alert state
                document.getElementById('password_alert').classList.remove('show');
                document.getElementById('generated_password_text').textContent = '-';
                document.getElementById('copy_btn_text').textContent = 'Salin';
                document.querySelector('.btn-copy-password').classList.remove('copied');

                // Fetch student data
                fetch(`/admin/students/${studentId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        populateModal(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal memuat data siswa. Silakan coba lagi.'
                        });
                        closeDetailModal();
                    });
            }

            function closeDetailModal() {
                const modal = document.getElementById('studentDetailModal');
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
                currentStudentId = null;

                // Reset password alert when modal closes
                document.getElementById('password_alert').classList.remove('show');
            }

            function populateModal(data) {
                // Avatar
                const avatar = document.getElementById('modal_avatar');
                avatar.textContent = data.name ? data.name.charAt(0).toUpperCase() : '?';

                // Basic Info
                document.getElementById('modal_name').textContent = data.name || '-';
                document.getElementById('modal_email').textContent = data.email || '-';
                document.getElementById('modal_student_id').textContent = 'ID Siswa: ' + (data.student_id || 'Belum ditetapkan');

                // Package Summary
                const packageSummary = document.getElementById('modal_package_summary');
                if (data.summary) {
                    packageSummary.innerHTML = `
                                        <div style="padding: 16px; background: rgba(255,255,255,0.7); border-radius: 12px;">
                                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px;">Paket Terbaru</div>
                                            <div style="font-weight: 700; font-size: 1.05rem; margin-bottom: 8px;">${data.summary.package}</div>
                                            <div style="font-size: 0.9rem; color: var(--text-muted);">Aktif hingga ${data.summary.expires}</div>
                                            <span class="status-pill" data-state="${data.summary.status_state}" style="margin-top: 12px;">${data.summary.status}</span>
                                        </div>
                                    `;
                } else {
                    packageSummary.innerHTML = '<p style="color: var(--text-muted);">Tidak ada paket aktif</p>';
                }

                // Contact Info
                document.getElementById('modal_username').textContent = data.username || 'Tidak tersedia';
                document.getElementById('modal_phone').textContent = data.phone || 'Tidak tersedia';
                document.getElementById('modal_parent_name').textContent = data.parent_name || 'Tidak tersedia';
                document.getElementById('modal_gender').textContent = data.gender ? capitalizeFirst(data.gender) : 'Tidak tersedia';
                document.getElementById('modal_address').textContent = data.address || 'Tidak tersedia';

                // Timeline
                const timelineContainer = document.getElementById('modal_timeline');
                if (data.timeline && data.timeline.length > 0) {
                    timelineContainer.innerHTML = data.timeline.map(entry => `
                                        <div class="timeline-item">
                                            <div class="timeline-item-header">${entry.package}</div>
                                            <span class="status-pill" data-state="${entry.status_state}">${entry.status}</span>
                                            <div style="color: var(--text-muted); font-size: 0.9rem; margin-top: 8px;">
                                                Periode: ${entry.period}
                                            </div>
                                            <div class="timeline-meta">
                                                Invoice #${entry.invoice || '-'} · Total ${entry.total}
                                            </div>
                                        </div>
                                    `).join('');
                } else {
                    timelineContainer.innerHTML = '<p style="color: var(--text-muted); text-align: center; padding: 20px;">Belum ada riwayat paket untuk siswa ini.</p>';
                }
            }

            function capitalizeFirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
            }

            // Close modal on outside click
            window.onclick = function (event) {
                const modal = document.getElementById('studentDetailModal');
                if (event.target === modal) {
                    closeDetailModal();
                }
            }

            // ========== PASSWORD FUNCTIONS ==========
            function generateNewPassword() {
                if (!currentStudentId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Tidak dapat menemukan ID siswa.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Generate Password Baru?',
                    html: 'Password lama siswa akan dihapus dan diganti dengan password baru.<br><br><strong>Lanjutkan?</strong>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Generate!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btn = document.getElementById('btn_generate_password');
                        const originalHtml = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = `
                                            <svg class="animate-spin" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Memproses...</span>
                                        `;

                        fetch(`/admin/students/${currentStudentId}/reset-password`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                btn.disabled = false;
                                btn.innerHTML = originalHtml;

                                if (data.success) {
                                    document.getElementById('generated_password_text').textContent = data.password;
                                    document.getElementById('password_alert').classList.add('show');

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    throw new Error(data.error || 'Terjadi kesalahan');
                                }
                            })
                            .catch(error => {
                                btn.disabled = false;
                                btn.innerHTML = originalHtml;

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: error.message || 'Gagal generate password baru.'
                                });
                            });
                    }
                });
            }

            function copyPassword() {
                const passwordText = document.getElementById('generated_password_text').textContent;
                if (!passwordText || passwordText === '-') {
                    return;
                }

                navigator.clipboard.writeText(passwordText).then(() => {
                    const btn = document.querySelector('.btn-copy-password');
                    const btnText = document.getElementById('copy_btn_text');

                    btn.classList.add('copied');
                    btnText.textContent = 'Tersalin!';

                    setTimeout(() => {
                        btn.classList.remove('copied');
                        btnText.textContent = 'Salin';
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = passwordText;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);

                    const btn = document.querySelector('.btn-copy-password');
                    const btnText = document.getElementById('copy_btn_text');
                    btn.classList.add('copied');
                    btnText.textContent = 'Tersalin!';

                    setTimeout(() => {
                        btn.classList.remove('copied');
                        btnText.textContent = 'Salin';
                    }, 2000);
                });
            }
        </script>
    @endpush
@endsection