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
            gap: 16px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.9rem;
            transition: all 0.2s;
            background: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        /* Table Styling */
        .table-responsive {
            overflow-x: auto;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 800px; /* Force scroll on small columns */
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
        .btn-save { background: var(--primary); color: white; }
        .btn-delete { background: #fee2e2; color: #b91c1c; }
        .btn-cancel { background: #f1f5f9; color: #64748b; }
        .btn-restore { background: #dcfce7; color: #15803d; }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            margin-bottom: 24px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            color: var(--text-main);
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .modal-footer button {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
            .main-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .footer-grid { grid-template-columns: 1fr; }
            .header-panel { flex-direction: column; align-items: flex-start; }
            .session-item { flex-direction: column; align-items: flex-start; gap: 12px; }
            .session-time { text-align: left; }
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
            <div class="metric-value" style="color: #ef4444;">{{ number_format($schedule['metrics']['cancelled']) }}</div>
        </div>
        <div class="metric-card">
            <span class="metric-label">Pola Aktif</span>
            <div class="metric-value" style="color: var(--primary);">{{ number_format($schedule['metrics']['templates']) }}</div>
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
                @if (! $schedule['selectedTutorId'])
                    <div class="empty-state">Silakan pilih tutor pada filter di atas untuk menambahkan jadwal.</div>
                @elseif ($schedule['packages']->isEmpty())
                    <div class="empty-state">Tutor ini belum memiliki paket belajar aktif.</div>
                @else
                    <form method="POST" action="{{ route('admin.schedule.templates.store') }}" class="form-stack">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $schedule['selectedTutorId'] }}">
                        
                        <div class="form-group">
                            <label>Paket Belajar</label>
                            <select name="package_id" id="package-select" class="form-control" required>
                                <option value="">Pilih Paket</option>
                                @foreach ($schedule['packages'] as $package)
                                    <option value="{{ $package->id }}" data-level="{{ $package->level }}" data-subjects="{{ $package->subjects->map(function($s) { return $s->id . ':' . $s->name . ':' . $s->level; })->join('|') }}">{{ $package->detail_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label>Judul Sesi</label>
                                <input type="text" name="title" class="form-control" placeholder="Contoh: Pertemuan 1" required>
                            </div>
                            <div class="form-group">
                                <label>Mata Pelajaran</label>
                                <select name="subject_id" id="subject-select" class="form-control" required disabled>
                                    <option value="">Pilih Mata Pelajaran</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label>Tingkat Kelas</label>
                                <select name="class_level" id="class-level-select" class="form-control" required disabled>
                                    <option value="">Pilih Tingkat Kelas</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Lokasi</label>
                                <select name="location" id="location-select" class="form-control" required>
                                    <option value="">Pilih Lokasi</option>
                                    <option value="Ruangan Kelas Offline">Ruangan Kelas Offline</option>
                                    <option value="Online (Ruang Virtual)">Online (Ruang Virtual)</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="reference_date" class="form-control" value="{{ $schedule['referenceDate'] }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jam Mulai</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label>Durasi (Menit)</label>
                                <input type="number" name="duration_minutes" class="form-control" value="90" min="30" step="15" required>
                            </div>
                            <div class="form-group">
                                <label>Jml. Siswa</label>
                                <input type="number" name="student_count" class="form-control" value="1" min="1">
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">Simpan Jadwal</button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Right Column: Active Templates Table --}}
        <div class="content-card">
            <div class="card-head">
                <h4>Pola Jadwal Aktif</h4>
                <span>Daftar jadwal berulang yang sedang berjalan</span>
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
                                    <form method="POST" action="{{ route('admin.schedule.templates.update', $template['id']) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="user_id" value="{{ $template['user_id'] }}">
                                        
                                        <td style="min-width: 200px;">
                                            <input type="text" name="title" value="{{ $template['title'] }}" class="table-input" style="font-weight: 600; margin-bottom: 4px;">
                                            <select name="package_id" class="table-input" style="font-size: 0.8rem; color: var(--text-muted);">
                                                @foreach ($schedule['packages'] as $package)
                                                    <option value="{{ $package->id }}" @selected($package->id === $template['package_id'])>
                                                        {{ $package->detail_title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="min-width: 140px;">
                                            <input type="text" name="category" value="{{ $template['category'] }}" class="table-input" placeholder="Mapel" style="margin-bottom: 4px;">
                                            <input type="text" name="location" value="{{ $template['location'] }}" class="table-input" placeholder="Lokasi">
                                        </td>
                                        <td style="min-width: 160px;">
                                            <div style="display: flex; gap: 4px; margin-bottom: 4px;">
                                                <input type="date" name="reference_date" value="{{ $template['reference_date_value'] ?? $schedule['referenceDate'] }}" class="table-input">
                                            </div>
                                            <div style="display: flex; gap: 4px;">
                                                <input type="time" name="start_time" value="{{ $template['start_time'] }}" class="table-input">
                                                <input type="number" name="duration_minutes" value="{{ $template['duration_minutes'] }}" class="table-input" style="width: 60px;">
                                            </div>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="action-btn-group" style="justify-content: flex-end;">
                                                <button type="submit" class="btn-sm btn-save">Simpan</button>
                                    </form>
                                                <form method="POST" action="{{ route('admin.schedule.templates.destroy', $template['id']) }}" onsubmit="return confirm('Hapus pola ini?');">
                                                    @csrf @method('DELETE')
                                                    <input type="hidden" name="redirect_tutor_id" value="{{ $schedule['activeFilter'] }}">
                                                    <button type="submit" class="btn-sm btn-delete">Hapus</button>
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
            @if (! $schedule['ready'])
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
                                                <button type="button" class="btn-sm btn-save" style="flex: 1;" onclick="openEditModal({{ json_encode($session) }})">Edit</button>
                                                <form method="POST" action="{{ route('admin.schedule.sessions.cancel', $session['id']) }}" onsubmit="return confirm('Batalkan sesi ini?');" style="flex: 1;">
                                                    @csrf
                                                    <input type="hidden" name="redirect_tutor_id" value="{{ $schedule['activeFilter'] }}">
                                                    <button type="submit" class="btn-sm btn-cancel" style="width: 100%;">Batalkan</button>
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
                        <button id="toggle-more-btn" class="btn-primary" style="width: auto; padding: 10px 24px;" onclick="toggleMoreDays()">
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
                                        <form method="POST" action="{{ route('admin.schedule.sessions.restore', $cancelled['id']) }}">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const packageSelect = document.getElementById('package-select');
    const subjectSelect = document.getElementById('subject-select');
    const classLevelSelect = document.getElementById('class-level-select');

    // Function to populate class level options based on package level
    function populateClassLevels(packageLevel) {
        classLevelSelect.innerHTML = '<option value="">Pilih Tingkat Kelas</option>';

        let classOptions = [];

        switch(packageLevel) {
            case 'SD':
                classOptions = [
                    {value: '1', label: 'Kelas 1'},
                    {value: '2', label: 'Kelas 2'},
                    {value: '3', label: 'Kelas 3'},
                    {value: '4', label: 'Kelas 4'},
                    {value: '5', label: 'Kelas 5'},
                    {value: '6', label: 'Kelas 6'}
                ];
                break;
            case 'SMP':
                classOptions = [
                    {value: '7', label: 'Kelas 7'},
                    {value: '8', label: 'Kelas 8'},
                    {value: '9', label: 'Kelas 9'}
                ];
                break;
            case 'SMA':
                classOptions = [
                    {value: '10', label: 'Kelas 10'},
                    {value: '11', label: 'Kelas 11'},
                    {value: '12', label: 'Kelas 12'}
                ];
                break;
            default:
                classOptions = [];
        }

        classOptions.forEach(function(optionData) {
            const option = document.createElement('option');
            option.value = optionData.value;
            option.textContent = optionData.label;
            classLevelSelect.appendChild(option);
        });

        classLevelSelect.disabled = classOptions.length === 0;
    }

    packageSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const packageLevel = selectedOption.getAttribute('data-level') || '';
        const subjectsData = selectedOption.getAttribute('data-subjects') || '';

        // Populate class levels
        populateClassLevels(packageLevel);

        // Populate subjects
        subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';

        if (subjectsData) {
            const subjects = subjectsData.split('|');
            subjects.forEach(function(subjectStr) {
                const [id, name, level] = subjectStr.split(':');
                const option = document.createElement('option');
                option.value = id;
                option.textContent = name + ' (' + level + ')';
                subjectSelect.appendChild(option);
            });
            subjectSelect.disabled = false;
        } else {
            subjectSelect.disabled = true;
        }
    });
});

// Edit Modal Functions
function openEditModal(session) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editSessionForm');
    
    // Set form action
    form.action = `/admin/schedule/${session.id}`;
    
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
    
    modal.classList.add('active');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('editModal')?.addEventListener('click', function(e) {
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

</script>
@endsection