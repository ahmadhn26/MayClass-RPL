@extends('admin.layout')

@section('title', 'Manajemen Siswa - MayClass')

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
                    <input type="text" name="q" placeholder="Cari nama atau email siswa..." value="{{ request('q') }}">
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
                            <th>Status Akun</th>
                            <th>Masa Berlaku</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>
                                    <div class="student-profile">
                                        {{-- Avatar Inisial --}}
                                        <div class="student-avatar">
                                            {{ substr($student['name'], 0, 1) }}
                                        </div>
                                        <div class="student-info">
                                            <span class="student-name">{{ $student['name'] }}</span>
                                            <span class="student-email">{{ $student['email'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="student-id-badge">
                                        {{ $student['student_id'] ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if(!empty($student['package']))
                                        <span style="font-weight: 600; color: var(--text-main);">{{ $student['package'] }}</span>
                                    @else
                                        <span style="color: var(--text-muted); font-style: italic;">Tidak ada paket</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-pill" data-state="{{ $student['status_state'] ?? 'inactive' }}">
                                        {{ $student['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div
                                        style="display: flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 0.85rem;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $student['ends_at'] ?? '-' }}
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn-detail" onclick="openDetailModal({{ $student['id'] }})">
                                        Detail
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-delete" data-id="{{ $student['id'] }}"
                                        data-name="{{ $student['name'] }}" data-active="{{ $student['status_state'] }}">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <svg style="width: 48px; height: 48px; margin-bottom: 16px; color: #cbd5e1;" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                        <p>Belum ada data siswa yang tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
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
                const deleteButtons = document.querySelectorAll('.btn-delete');
                const deleteForm = document.getElementById('delete-form');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const studentId = this.dataset.id;
                        const studentName = this.dataset.name;
                        const isActive = this.dataset.active === 'active'; // Check if active

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
                    });
                });

                // Show success/error messages from session
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
            function openDetailModal(studentId) {
                const modal = document.getElementById('studentDetailModal');
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';

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
            window.onclick = function(event) {
                const modal = document.getElementById('studentDetailModal');
                if (event.target === modal) {
                    closeDetailModal();
                }
            }
        </script>
    @endpush
@endsection