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
            padding: 10px 16px 10px 40px; /* Space for icon */
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
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
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
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
                                    <div style="display: flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 0.85rem;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $student['ends_at'] ?? '-' }}
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <a class="btn-detail" href="{{ route('admin.students.show', $student['id']) }}">
                                        Detail
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <svg style="width: 48px; height: 48px; margin-bottom: 16px; color: #cbd5e1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
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
@endsection