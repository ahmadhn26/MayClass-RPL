@extends('admin.layout')

@section('title', 'Manajemen Jadwal - Admin MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --primary-light: #ccfbf1;
            --primary-hover: #115e59;
            --bg-surface: #ffffff;
            --bg-body: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        .schedule-container {
            display: flex;
            flex-direction: column;
            gap: 32px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* --- 1. HEADER & FILTER --- */
        .header-panel {
            background: var(--bg-surface);
            border-radius: var(--radius);
            padding: 24px 32px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .header-content h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 8px 0;
            letter-spacing: -0.02em;
        }

        .header-content p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.95rem;
            max-width: 700px;
            line-height: 1.5;
        }

        .filter-box {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bg-body);
            padding: 8px 16px;
            border-radius: 99px;
            border: 1px solid var(--border-color);
        }

        .filter-box label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .filter-select {
            background: transparent;
            border: none;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            cursor: pointer;
            outline: none;
            min-width: 150px;
        }

        /* --- 2. METRICS --- */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .metric-card {
            background: var(--bg-surface);
            padding: 20px 24px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: transform 0.2s;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            border-color: var(--primary);
        }

        .metric-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
        }

        /* --- 3. MAIN CONTENT GRID --- */
        .main-grid {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 32px;
            align-items: start;
        }

        /* Common Card Styles */
        .content-card {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .card-head {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            background: #fcfcfc;
        }

        .card-head h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .card-head span {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 4px;
            display: block;
        }

        .card-body {
            padding: 24px;
        }

        /* Form Styling */
        .form-stack {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group label small {
            font-weight: 500;
            font-size: 0.75rem;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.9rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fff;
            font-family: inherit;
        }

        .form-control:hover {
            border-color: #94a3b8;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            transform: translateY(-1px);
        }

        /* Zoom Link Container with smooth animation */
        #zoom-link-container {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: -8px;
        }

        #zoom-link-container.show {
            max-height: 200px;
            opacity: 1;
            margin-top: 0;
        }

        #zoom-link-container small {
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .btn-primary {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
            box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, #0d5753 100%);
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
        }

        /* Table Styling */
        .table-responsive {
            overflow-x: auto;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 800px;
            /* Force scroll on small columns */
        }

        .modern-table th {
            background: #f8fafc;
            text-align: left;
            padding: 12px 16px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 700;
            border-bottom: 1px solid var(--border-color);
        }

        .modern-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        .modern-table tr:last-child td {
            border-bottom: none;
        }

        .table-input {
            padding: 6px 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.85rem;
            width: 100%;
        }

        .action-btn-group {
            display: flex;
            gap: 6px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .btn-save {
            background: var(--primary);
            color: white;
        }

        .btn-delete {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #64748b;
        }

        .btn-restore {
            background: #dcfce7;
            color: #15803d;
        }

        /* Modern Delete All Button */
        .btn-delete-all {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
            white-space: nowrap;
        }

        .btn-delete-all:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.35);
        }

        .btn-delete-all:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
        }

        .btn-delete-all svg {
            flex-shrink: 0;
        }

        /* Modal Styles */
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
            padding: 0;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            border-radius: 20px 20px 0 0;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .modal-body {
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .modal-footer {
            padding: 24px 32px;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            background: #f8fafc;
            border-radius: 0 0 20px 20px;
        }

        .modal-footer button {
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 1rem;
            transition: all 0.3s;
            flex: 1;
            /* Make buttons consistent width */
        }

        .modal-footer .btn-primary {
            width: auto;
            /* Reset global width */
            margin-top: 0;
            /* Reset global margin */
        }

        .modal-footer .btn-cancel {
            background: transparent;
            color: var(--text-muted);
        }

        .modal-footer .btn-cancel:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        .modal-footer .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        }

        .modal-footer .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 118, 110, 0.4);
        }

        .modal-footer .btn-submit:active {
            transform: translateY(0);
        }

        /* --- 4. AGENDA TIMELINE --- */
        .timeline-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .timeline-day {
            position: relative;
            padding-left: 24px;
        }

        /* Timeline line */
        .timeline-day::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--border-color);
        }

        .day-header {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 16px;
        }

        .day-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .day-date {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .session-list {
            display: grid;
            gap: 12px;
        }

        .session-item {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        /* Colored Left Border */
        .session-item::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary);
        }

        .session-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transform: translateX(4px);
        }

        .session-info h5 {
            margin: 0 0 4px 0;
            font-size: 1rem;
            color: var(--text-main);
        }

        .session-details {
            font-size: 0.85rem;
            color: var(--text-muted);
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .dot-sep {
            width: 4px;
            height: 4px;
            background: #cbd5e1;
            border-radius: 50%;
        }

        .session-time {
            text-align: right;
            min-width: 120px;
        }

        .time-range {
            font-weight: 700;
            color: var(--text-main);
            font-size: 0.95rem;
            display: block;
        }

        .time-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: block;
            margin-bottom: 4px;
        }

        /* --- 5. FOOTER GRIDS --- */
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--text-muted);
            background: #f8fafc;
            border: 1px dashed var(--border-color);
            border-radius: var(--radius);
            font-size: 0.9rem;
        }

        @media (max-width: 1200px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .header-panel {
                flex-direction: column;
                align-items: flex-start;
            }

            .session-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .session-time {
                text-align: left;
            }
        }

        /* Modern Error Alert */
        .error-alert {
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            animation: slideDown 0.3s ease-out;
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

        .error-header {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #dc2626;
            margin-bottom: 12px;
        }

        .error-header svg {
            flex-shrink: 0;
        }

        .error-header strong {
            font-size: 1rem;
            font-weight: 600;
        }

        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error-list li {
            color: #991b1b;
            font-size: 0.9rem;
            padding: 6px 0 6px 36px;
            position: relative;
            line-height: 1.5;
        }

        .error-list li:before {
            content: "•";
            position: absolute;
            left: 16px;
            font-weight: 700;
            color: #dc2626;
        }
    </style>
@endpush

@section('content')
    <div class="schedule-container">

        {{-- 1. Header & Filter --}}
        <div class="header-panel">
            <div class="header-content">
                <h3>Kalender Pengajaran</h3>
                <p>Tinjau jadwal, atur pola pertemuan, dan kelola sesi tutor secara terpusat.</p>
            </div>
            @if ($schedule['tutors']->isNotEmpty())
                <form method="GET" action="{{ route('admin.schedules.index') }}">
                    <div class="filter-box">
                        <label>Filter Tutor:</label>
                        <select name="tutor_id" onchange="this.form.submit()" class="filter-select">
                            <option value="all" @selected($schedule['activeFilter'] === 'all')>Semua Tutor</option>
                            @foreach ($schedule['tutors'] as $tutor)
                                <option value="{{ $tutor->id }}" @selected($schedule['activeFilter'] === (string) $tutor->id)>
                                    {{ $tutor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            @endif
        </div>

        {{-- 2. Metrics --}}
        <div class="metrics-grid">
            <div class="metric-card">
                <span class="metric-label">Akan Datang</span>
                <div class="metric-value">{{ number_format($schedule['metrics']['upcoming']) }}</div>
            </div>
            <div class="metric-card">
                <span class="metric-label">Selesai</span>
                <div class="metric-value">{{ number_format($schedule['metrics']['history']) }}</div>
            </div>
            <div class="metric-card">
                <span class="metric-label">Dibatalkan</span>
                <div class="metric-value" style="color: #ef4444;">{{ number_format($schedule['metrics']['cancelled']) }}
                </div>
            </div>
            <div class="metric-card">
                <span class="metric-label">Pola Aktif</span>
                <div class="metric-value" style="color: var(--primary);">
                    {{ number_format($schedule['metrics']['templates']) }}
                </div>
            </div>
        </div>

        {{-- 3. Main Management Area --}}
        <div class="main-grid">

            {{-- Left Column: Form Input --}}
            <div class="content-card">
                <div class="card-head">
                    <h4>Tambah Jadwal Baru</h4>
                    <span>Buat pola berulang untuk tutor terpilih</span>
                </div>
                <div class="card-body">
                    @if (!$schedule['selectedTutorId'])
                        <div class="empty-state">Silakan pilih tutor pada filter di atas untuk menambahkan jadwal.</div>
                    @elseif ($schedule['packages']->isEmpty())
                        <div class="empty-state">Tutor ini belum memiliki paket belajar aktif. Silahkan buka Manajemen Paket dan
                            tambahkan tutor pengampuhnya</div>
                    @else
                        <form method="POST" action="{{ route('admin.schedule.templates.store') }}" class="form-stack">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $schedule['selectedTutorId'] }}">

                            {{-- Modern Error Alert --}}
                            @if($errors->any())
                                <div class="error-alert">
                                    <div class="error-header">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <strong>Gagal menyimpan jadwal!</strong>
                                    </div>
                                    <ul class="error-list">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Paket Belajar</label>
                                <select name="package_id" id="package-select" class="form-control" required>
                                    <option value="">Pilih Paket</option>
                                    @foreach ($schedule['packages'] as $package)
                                                        <option value="{{ $package->id }}" data-level="{{ $package->level }}" data-subjects="{{ $package->subjects->map(function ($s) {
                                        return $s->id . ':' . $s->name . ':' . $s->level; })->join('|') }}">
                                                            {{ $package->detail_title }}
                                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Judul Sesi & Mata Pelajaran --}}
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Judul Sesi</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="Contoh: Pertemuan Rutin Matematika" required>
                                </div>

                                <div class="form-group">
                                    <label>Mata Pelajaran</label>
                                    <select name="subject_id" id="subject-select" class="form-control" disabled required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Lokasi (Full Width karena penting) --}}
                            <div class="form-group">
                                <label>Lokasi Pembelajaran</label>
                                <select name="location" id="location-select" class="form-control" required>
                                    <option value="">Pilih Lokasi</option>
                                    <option value="Ruangan Kelas Offline">Ruangan Kelas Offline</option>
                                    <option value="Online (Ruang Virtual)">Online (Ruang Virtual)</option>
                                </select>
                            </div>

                            {{-- Link Zoom (Conditional - Hanya muncul jika Online dipilih) --}}
                            <div class="form-group" id="zoom-link-container">
                                <label>Link Zoom Meeting <small style="color: var(--text-muted);">(Wajib untuk kelas
                                        online)</small></label>
                                <input type="url" name="zoom_link" id="zoom-link-input" class="form-control"
                                    placeholder="https://zoom.us/j/...">
                                <small style="color: var(--text-muted); margin-top: 4px; display: block;">Masukkan link Zoom
                                    untuk pertemuan virtual</small>
                            </div>

                            {{-- Tanggal & Jam Mulai --}}
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="reference_date" class="form-control"
                                        value="{{ $schedule['referenceDate'] }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Jam Mulai</label>
                                    <input type="time" name="start_time" class="form-control" required>
                                </div>
                            </div>

                            {{-- Durasi & Jumlah Siswa --}}
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="form-group">
                                    <label>Durasi (Menit)</label>
                                    <input type="number" name="duration_minutes" class="form-control" value="90" min="30"
                                        step="15" required>
                                </div>
                                <div class="form-group">
                                    <label>Jml. Siswa</label>
                                    <input type="number" name="student_count" class="form-control" value="1" min="1">
                                </div>
                            </div>

                            <button type="submit" class="btn-primary" id="submit-schedule-btn">✓ Simpan Jadwal</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Right Column: Active Templates Table --}}
            <div class="content-card">
                <div class="card-head"
                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h4>Pola Jadwal Aktif</h4>
                        <span>Daftar jadwal berulang yang sedang berjalan</span>
                    </div>
                    @if ($schedule['templates']->isNotEmpty() && $schedule['selectedTutorId'])
                        <form method="POST" action="{{ route('admin.schedule.templates.destroyAll') }}"
                            id="delete-all-templates-form">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="user_id" value="{{ $schedule['selectedTutorId'] }}">
                            <input type="hidden" name="redirect_tutor_id" value="{{ $schedule['activeFilter'] }}">
                            <button type="button" class="btn-delete-all" id="btn-delete-all-templates">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                                Hapus Semua Jadwal
                            </button>
                        </form>
                    @endif
                </div>
                @if ($schedule['templates']->isNotEmpty())
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Judul & Paket</th>
                                        <th>Detail</th>
                                        <th>Waktu</th>
                                        <th style="text-align: right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedule['templates'] as $template)
                                                    <tr>
                                                        <form method="POST"
                                                            action="{{ route('admin.schedule.templates.update', $template['id']) }}">
                                                            @csrf @method('PUT')
                                                            <input type="hidden" name="user_id" value="{{ $template['user_id'] }}">
                                                            <input type="hidden" name="subject_id" value="{{ $template['subject_id'] ?? '' }}">

                                                            <td style="min-width: 200px;">
                                                                <input type="text" name="title" value="{{ $template['title'] }}" class="table-input"
                                                                    style="font-weight: 600; margin-bottom: 4px;">
                                                                <select name="package_id" class="table-input"
                                                                    style="font-size: 0.8rem; color: var(--text-muted);">
                                                                    @foreach ($schedule['packages'] as $package)
                                                                        <option value="{{ $package->id }}"
                                                                            @selected($package->id === $template['package_id'])>
                                                                            {{ $package->detail_title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td style="min-width: 140px;">
                                                                <input type="text" name="category" value="{{ $template['category'] }}"
                                                                    class="table-input" placeholder="Mapel" style="margin-bottom: 4px;">
                                                                <input type="text" name="location" value="{{ $template['location'] }}"
                                                                    class="table-input" placeholder="Lokasi">
                                                            </td>
                                                            <td style="min-width: 160px;">
                                                                <div style="display: flex; gap: 4px; margin-bottom: 4px;">
                                                                    <input type="date" name="reference_date"
                                                                        value="{{ $template['reference_date_value'] ?? $schedule['referenceDate'] }}"
                                                                        class="table-input">
                                                                </div>
                                                                <div style="display: flex; gap: 4px;">
                                                                    <input type="time" name="start_time" value="{{ $template['start_time'] }}"
                                                                        class="table-input">
                                                                    <input type="number" name="duration_minutes"
                                                                        value="{{ $template['duration_minutes'] }}" class="table-input"
                                                                        style="width: 60px;">
                                                                </div>
                                                            </td>
                                                            <td style="text-align: right;">
                                                                <div class="action-btn-group" style="justify-content: flex-end;">
                                                                    <button type="submit" class="btn-sm btn-save">Simpan</button>
                                                        </form>
                                                        <form method="POST"
                                                            action="{{ route('admin.schedule.templates.destroy', $template['id']) }}"
                                                            class="delete-schedule-form">
                                                            @csrf @method('DELETE')
                                                            <input type="hidden" name="redirect_tutor_id" value="{{ $schedule['activeFilter'] }}">
                                                            <button type="button" class="btn-sm btn-delete btn-delete-schedule">Hapus</button>
                                                        </form>
                                        </div>
                                        </td>
                                        </tr>
                                    @endforeach
                        </tbody>
                        </table>
                    </div>
                @else
                <div class="empty-state" style="border: none; background: transparent;">Belum ada pola jadwal aktif.</div>
            @endif
        </div>

    </div>

    {{-- 4. Agenda Timeline --}}
    <div class="content-card">
        <div class="card-head">
            <h4>Agenda Mendatang</h4>
            <span>Jadwal sesi berdasarkan urutan waktu</span>
        </div>
        <div class="card-body">
            @if (!$schedule['ready'])
                <div class="empty-state">Sistem belum siap. Jalankan migrasi terlebih dahulu.</div>
            @elseif ($schedule['upcomingDays']->isEmpty())
                <div class="empty-state">Belum ada sesi mendatang yang dijadwalkan.</div>
            @else
                <div class="timeline-container" id="timeline-container">
                    @foreach ($schedule['upcomingDays'] as $index => $day)
                        <div class="timeline-day" data-day-index="{{ $index }}" style="{{ $index >= 7 ? 'display: none;' : '' }}">
                            <div class="day-header">
                                <span class="day-name">{{ $day['weekday'] }}</span>
                                <span class="day-date">{{ $day['full_date'] }}</span>
                            </div>
                            <div class="session-list">
                                @foreach ($day['items'] as $session)
                                    <div class="session-item">
                                        <div class="session-info">
                                            <h5>{{ $session['title'] }}</h5>
                                            <div class="session-details">
                                                <span>{{ $session['subject'] }}</span>
                                                <span class="dot-sep"></span>
                                                <span>{{ $session['tutor'] }}</span>
                                                <span class="dot-sep"></span>
                                                <span>{{ $session['location'] }}</span>
                                            </div>
                                        </div>
                                        <div class="session-time">
                                            <span class="time-label">Waktu Belajar</span>
                                            <span class="time-range">{{ $session['time_range'] }}</span>
                                            <div style="display: flex; gap: 4px; margin-top: 6px;">
                                                <button type="button" class="btn-sm btn-save" style="flex: 1;"
                                                    onclick="openEditModal('{{ base64_encode(json_encode($session)) }}')">Edit</button>
                                                <form method="POST"
                                                    action="{{ route('admin.schedule.sessions.cancel', $session['id']) }}"
                                                    onsubmit="return confirm('Batalkan sesi ini?');" style="flex: 1;">
                                                    @csrf
                                                    <input type="hidden" name="redirect_tutor_id"
                                                        value="{{ $schedule['activeFilter'] }}">
                                                    <button type="submit" class="btn-sm btn-cancel"
                                                        style="width: 100%;">Batalkan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($schedule['upcomingDays']->count() > 7)
                    <div style="text-align: center; margin-top: 20px;">
                        <button id="toggle-more-btn" class="btn-primary" style="width: auto; padding: 10px 24px;"
                            onclick="toggleMoreDays()">
                            Tampilkan Lebih Banyak ({{ $schedule['upcomingDays']->count() - 7 }} hari lagi)
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- 5. History & Cancelled (Side by Side) --}}
    <div class="footer-grid">
        {{-- History --}}
        <div class="content-card">
            <div class="card-head">
                <h4>Histori Selesai</h4>
            </div>
            @if ($schedule['historySessions']->isNotEmpty())
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pengajar</th>
                                <th>Paket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedule['historySessions'] as $history)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $history['label'] }}</div>
                                        <small style="color: var(--text-muted);">{{ $history['time_range'] }}</small>
                                    </td>
                                    <td>{{ $history['tutor'] }}</td>
                                    <td>{{ $history['package'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">Belum ada riwayat.</div>
            @endif
        </div>

        {{-- Cancelled --}}
        <div class="content-card">
            <div class="card-head">
                <h4>Sesi Dibatalkan</h4>
            </div>
            @if ($schedule['cancelledSessions']->isNotEmpty())
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Waktu Awal</th>
                                <th>Pengajar</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedule['cancelledSessions'] as $cancelled)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $cancelled['label'] }}</div>
                                        <small style="color: var(--text-muted);">{{ $cancelled['time_range'] }}</small>
                                    </td>
                                    <td>{{ $cancelled['tutor'] }}</td>
                                    <td style="text-align: right;">
                                        <form method="POST"
                                            action="{{ route('admin.schedule.sessions.restore', $cancelled['id']) }}">
                                            @csrf
                                            <input type="hidden" name="redirect_tutor_id" value="{{ $schedule['activeFilter'] }}">
                                            <button type="submit" class="btn-sm btn-restore">Pulihkan</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">Tidak ada sesi batal.</div>
            @endif
        </div>
    </div>

    </div>

    {{-- Edit Session Modal --}}
    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Jadwal Sesi</h3>
            </div>
            <form id="editSessionForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_tutor_id" value="{{ $schedule['activeFilter'] }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" id="edit_start_time" class="form-control" required>
                    </div>
                    @if ($schedule['tutors']->count() > 1)
                        <div class="form-group">
                            <label>Tutor (Opsional - kosongkan jika tidak ingin mengubah)</label>
                            <select name="user_id" id="edit_user_id" class="form-control">
                                <option value="">Tidak diubah</option>
                                @foreach ($schedule['tutors'] as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Zoom Link (Hidden by default, shown via JS if location is Online) --}}
                    <div class="form-group" id="edit-zoom-container" style="display: none;">
                        <label>Link Meeting / Zoom (Opsional - kosongkan jika tidak ingin mengubah)</label>
                        <input type="url" name="zoom_link" id="edit_zoom_link" class="form-control"
                            placeholder="https://zoom.us/j/...">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Base64 Helper
        const Base64 = {
            _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
            encode: function (e) {
                var t = "";
                var n, r, i, s, o, u, a;
                var f = 0;
                e = Base64._utf8_encode(e);
                while (f < e.length) {
                    n = e.charCodeAt(f++);
                    r = e.charCodeAt(f++);
                    i = e.charCodeAt(f++);
                    s = n >> 2;
                    o = (n & 3) << 4 | r >> 4;
                    u = (r & 15) << 2 | i >> 6;
                    a = i & 63;
                    if (isNaN(r)) {
                        u = a = 64
                    } else if (isNaN(i)) {
                        a = 64
                    }
                    t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
                }
                return t
            },
            decode: function (e) {
                var t = "";
                var n, r, i;
                var s, o, u, a;
                var f = 0;
                e = e.replace(/[^A-Za-z0-9\+\/=]/g, "");
                while (f < e.length) {
                    s = this._keyStr.indexOf(e.charAt(f++));
                    o = this._keyStr.indexOf(e.charAt(f++));
                    u = this._keyStr.indexOf(e.charAt(f++));
                    a = this._keyStr.indexOf(e.charAt(f++));
                    n = s << 2 | o >> 4;
                    r = (o & 15) << 4 | u >> 2;
                    i = (u & 3) << 6 | a;
                    t = t + String.fromCharCode(n);
                    if (u != 64) {
                        t = t + String.fromCharCode(r)
                    }
                    if (a != 64) {
                        t = t + String.fromCharCode(i)
                    }
                }
                t = Base64._utf8_decode(t);
                return t
            },
            _utf8_encode: function (e) {
                e = e.replace(/\r\n/g, "\n");
                var t = "";
                for (var n = 0; n < e.length; n++) {
                    var r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r)
                    } else if (r > 127 && r < 2048) {
                        t += String.fromCharCode(r >> 6 | 192);
                        t += String.fromCharCode(r & 63 | 128)
                    } else {
                        t += String.fromCharCode(r >> 12 | 224);
                        t += String.fromCharCode(r >> 6 & 63 | 128);
                        t += String.fromCharCode(r & 63 | 128)
                    }
                }
                return t
            },
            _utf8_decode: function (e) {
                var t = "";
                var n = 0;
                var r = c1 = c2 = 0;
                while (n < e.length) {
                    r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r);
                        n++
                    } else if (r > 191 && r < 224) {
                        c2 = e.charCodeAt(n + 1);
                        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                        n += 2
                    } else {
                        c2 = e.charCodeAt(n + 1);
                        c3 = e.charCodeAt(n + 2);
                        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                        n += 3
                    }
                }
                return t
            },
            encodeJson: function (data) {
                return this.encode(JSON.stringify(data));
            },
            decodeJson: function (str) {
                return JSON.parse(this.decode(str));
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            // ========== CHECK FOR ERRORS ==========
            @if($errors->any())
                console.error('VALIDATION ERRORS DETECTED:', @json($errors->all()));
            @else
                console.log('No validation errors');
            @endif

            // ========== SWEETALERT ERROR NOTIFICATION ==========
            @if($errors->any())
                try {
                    console.log('Showing error notification');
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan Jadwal!',
                        html: `<ul style="text-align: left; margin: 0; padding-left: 20px;">
                                                                        @foreach($errors->all() as $error)
                                                                            <li style="margin-bottom: 8px;">{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>`,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Mengerti'
                    });
                } catch (error) {
                    console.error('SweetAlert error:', error);
                }
            @endif

                                // ========== SUCCESS NOTIFICATION FROM QUERY PARAMETER ==========
                                // Check URL for success parameter (more reliable than session flash)
                                const urlParams = new URLSearchParams(window.location.search);
            const successParam = urlParams.get('success');
            const messageParam = urlParams.get('message');

            if (successParam === '1') {
                try {
                    const successMessage = messageParam || 'Jadwal berhasil ditambahkan!';
                    console.log('Success parameter detected:', successMessage);

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: decodeURIComponent(successMessage),
                        confirmButtonColor: '#0f766e',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        // Remove success parameter from URL after showing notification
                        const cleanUrl = window.location.pathname + '?tutor_id=' + urlParams.get('tutor_id');
                        window.history.replaceState({}, '', cleanUrl);
                    });
                } catch (error) {
                    console.error('SweetAlert error:', error);
                }
            }

            // Fallback: Check session (for backward compatibility)
            @if(session('success') || session('status'))
                try {
                    const successMessage = '{{ session('success') ?? session('status') }}';
                    console.log('Session success detected:', successMessage);

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: successMessage,
                        confirmButtonColor: '#0f766e',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    });
                } catch (error) {
                    console.error('SweetAlert error:', error);
                }
            @endif

                                const packageSelect = document.getElementById('package-select');
            const subjectSelect = document.getElementById('subject-select');
            const locationSelect = document.getElementById('location-select');
            const zoomLinkContainer = document.getElementById('zoom-link-container');
            const zoomLinkInput = document.getElementById('zoom-link-input');

            // ========== ZOOM LINK CONDITIONAL DISPLAY ==========
            // Show/hide Zoom link input based on location selection
            locationSelect.addEventListener('change', function () {
                const isOnline = this.value.toLowerCase().includes('online');

                if (isOnline) {
                    zoomLinkContainer.classList.add('show');
                    zoomLinkInput.setAttribute('required', 'required');
                } else {
                    zoomLinkContainer.classList.remove('show');
                    zoomLinkInput.removeAttribute('required');
                    zoomLinkInput.value = ''; // Clear value if switching to offline
                }
            });

            // ========== PACKAGE & SUBJECT LOGIC ==========
            packageSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const subjectsData = selectedOption.getAttribute('data-subjects') || '';

                // Populate subjects dropdown with ID as value
                subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';

                if (subjectsData) {
                    const subjects = subjectsData.split('|');
                    subjects.forEach(function (subjectStr) {
                        const [id, name, level] = subjectStr.split(':');
                        const option = document.createElement('option');
                        // IMPORTANT: Use ID as value for direct submission
                        option.value = id;
                        option.textContent = name + ' (' + level + ')';
                        subjectSelect.appendChild(option);
                    });
                    subjectSelect.disabled = false;
                } else {
                    subjectSelect.disabled = true;
                }
            });

            // ========== BASE64 AJAX FORM SUBMISSION ==========
            const scheduleForm = document.querySelector('form[action*="schedule/template"]');
            if (scheduleForm) {
                console.log('✓ Base64 form submission handler attached');

                scheduleForm.addEventListener('submit', function (e) {
                    e.preventDefault(); // Always prevent default - we'll submit via AJAX
                    console.log('=== FORM SUBMIT ATTEMPT (Base64 AJAX) ===');

                    // Get all form data
                    const formData = new FormData(scheduleForm);
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }

                    console.log('Form data:', data);

                    // Check required fields
                    const requiredFields = {
                        'user_id': 'Tutor ID',
                        'package_id': 'Paket Belajar',
                        'title': 'Judul Sesi',
                        'subject_id': 'Mata Pelajaran',
                        'location': 'Lokasi',
                        'reference_date': 'Tanggal',
                        'start_time': 'Jam Mulai',
                        'duration_minutes': 'Durasi'
                    };

                    const missing = [];
                    for (let [field, label] of Object.entries(requiredFields)) {
                        if (!data[field] || data[field].toString().trim() === '') {
                            missing.push(label);
                            console.error('Missing field:', label, '(' + field + ')');
                        }
                    }

                    if (missing.length > 0) {
                        console.error('SUBMIT BLOCKED - Missing fields:', missing);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Belum Lengkap',
                            html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">' +
                                missing.map(f => '<li>' + f + '</li>').join('') +
                                '</ul>',
                            confirmButtonColor: '#f59e0b',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }

                    // Disable button and show loading
                    const submitBtn = document.getElementById('submit-schedule-btn');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '⏳ Menyimpan...';

                    // Encode data to Base64
                    const payload = Base64.encodeJson(data);
                    console.log('Base64 payload created, sending...');

                    // Submit via AJAX
                    fetch(scheduleForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': data._token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ payload: payload })
                    })
                        .then(response => {
                            console.log('Response status:', response.status);
                            return response.json();
                        })
                        .then(result => {
                            console.log('Response received:', result);

                            if (result.response) {
                                const decoded = Base64.decodeJson(result.response);
                                console.log('Decoded response:', decoded);

                                if (decoded.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: decoded.message || 'Jadwal berhasil ditambahkan!',
                                        confirmButtonColor: '#0f766e',
                                        confirmButtonText: 'OK',
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        // Redirect to success URL
                                        if (decoded.redirect) {
                                            window.location.href = decoded.redirect;
                                        } else {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    // Show error
                                    let errorHtml = decoded.message || 'Terjadi kesalahan';
                                    if (decoded.errors && decoded.errors.length > 0) {
                                        errorHtml = '<ul style="text-align: left; margin: 0; padding-left: 20px;">' +
                                            decoded.errors.map(err => '<li style="margin-bottom: 8px;">' + err + '</li>').join('') +
                                            '</ul>';
                                    }

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal Menyimpan Jadwal!',
                                        html: errorHtml,
                                        confirmButtonColor: '#ef4444',
                                        confirmButtonText: 'Mengerti'
                                    });

                                    // Re-enable button
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalBtnText;
                                }
                            } else {
                                // Unexpected response format
                                console.error('Unexpected response format:', result);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Format respons tidak dikenali',
                                    confirmButtonColor: '#ef4444'
                                });
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Koneksi Gagal',
                                text: 'Gagal menghubungi server. Silakan coba lagi.',
                                confirmButtonColor: '#ef4444',
                                confirmButtonText: 'OK'
                            });
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        });
                });
            } else {
                console.error('✗ Schedule form not found!');
            }
        });

        // Edit Modal Functions
        function openEditModal(encodedSession) {
            // Check if input is string (Base64) or object (legacy/direct)
            let session;
            try {
                if (typeof encodedSession === 'string') {
                    // Decide if it's base64 (no space, usually)
                    session = Base64.decodeJson(encodedSession);
                } else {
                    session = encodedSession;
                }
            } catch (e) {
                console.error("Error decoding session data", e);
                alert("Gagal memuat data sesi.");
                return;
            }

            const modal = document.getElementById('editModal');
            const form = document.getElementById('editSessionForm');
            const zoomContainer = document.getElementById('edit-zoom-container');
            const zoomInput = document.getElementById('edit_zoom_link');

            // Set form action
            form.action = `/admin/schedule/${session.id}`;
            form.setAttribute('data-action', `/admin/schedule/${session.id}`); // Store for JS use

            // Parse the start_iso to get date and time
            if (session.start_iso) {
                const startDate = new Date(session.start_iso);
                const dateStr = startDate.toISOString().split('T')[0];
                const timeStr = startDate.toTimeString().split(' ')[0].substring(0, 5);

                document.getElementById('edit_start_date').value = dateStr;
                document.getElementById('edit_start_time').value = timeStr;
            }

            // Reset tutor selection if exists
            const tutorSelect = document.getElementById('edit_user_id');
            if (tutorSelect) {
                tutorSelect.value = '';
            }

            // Handle Zoom Link & Location
            if (session.location && session.location.includes('Online')) {
                zoomContainer.style.display = 'block';
                zoomInput.value = session.zoom_link || ''; // Populate if exists
            } else {
                zoomContainer.style.display = 'none';
                zoomInput.value = '';
            }

            modal.classList.add('active');
        }

        // Handle Edit Form Submission with Base64
        document.getElementById('editSessionForm')?.addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Encode Payload
            const payload = Base64.encodeJson(data);
            const actionUrl = this.getAttribute('data-action');

            fetch(actionUrl, {
                method: 'POST', // Method spoofing for PUT
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    payload: payload
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.response) {
                        const decoded = Base64.decodeJson(data.response);
                        if (decoded.status === 'success') {
                            window.location.reload(); // Reload to show changes
                        } else {
                            alert('Gagal: ' + (decoded.message || 'Terjadi kesalahan'));
                        }
                    } else {
                        // Fallback for non-base64 error responses
                        alert('Terjadi kesalahan pada server.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menyimpan perubahan.');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        });

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('editModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Toggle show more/less days
        let showingAll = false;
        function toggleMoreDays() {
            const days = document.querySelectorAll('.timeline-day');
            const btn = document.getElementById('toggle-more-btn');

            showingAll = !showingAll;

            days.forEach((day, index) => {
                if (index >= 7) {
                    day.style.display = showingAll ? 'block' : 'none';
                }
            });

            if (showingAll) {
                btn.textContent = 'Tampilkan Lebih Sedikit';
            } else {
                const hiddenCount = days.length - 7;
                btn.textContent = `Tampilkan Lebih Banyak (${hiddenCount} hari lagi)`;
            }
        }

        // Handle delete template with SweetAlert
        document.addEventListener('DOMContentLoaded', function () {
            // Use click event on button instead of form submit
            document.querySelectorAll('.btn-delete-schedule').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Hapus Pola Jadwal?',
                        text: 'Pola jadwal ini akan dihapus dan sesi mendatang akan dibatalkan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Handle delete ALL templates button
            const deleteAllBtn = document.getElementById('btn-delete-all-templates');
            if (deleteAllBtn) {
                deleteAllBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    const form = document.getElementById('delete-all-templates-form');
                    const templateCount = {{ $schedule['templates']->count() ?? 0 }};

                    Swal.fire({
                        title: 'Hapus Semua Pola Jadwal?',
                        html: `<p>Anda akan menghapus <strong>${templateCount} pola jadwal</strong> untuk tutor ini.</p>
                                           <p style="color: #ef4444; font-size: 0.9rem; margin-top: 12px;">⚠️ Semua sesi mendatang juga akan dibatalkan!</p>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus Semua!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });

    </script>
@endsection