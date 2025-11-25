@extends('admin.layout')

@section('title', 'Manajemen Keuangan - MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --primary-light: #ccfbf1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-surface: #ffffff;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        .finance-container {
            display: flex;
            flex-direction: column;
            gap: 32px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* --- 1. HEADER --- */
        .page-header {
            background: var(--bg-surface);
            padding: 24px 32px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 4px 0;
        }

        .header-content p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* --- 2. STATS CARDS --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
        }

        .stat-card {
            background: var(--bg-surface);
            padding: 24px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: var(--primary);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--bg-body);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Status Summary row within Stats */
        .status-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .status-box {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 4px;
        }

        .status-box strong {
            font-size: 1.25rem;
            color: var(--text-main);
        }

        .status-box span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 99px;
        }

        .bg-pending { background: #fffbeb; color: #b45309; }
        .bg-paid { background: #ecfdf5; color: #047857; }
        .bg-rejected { background: #fef2f2; color: #b91c1c; }
        .bg-failed { background: #f1f5f9; color: #475569; }

        /* --- 3. CHART SECTION --- */
        .chart-card {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            padding: 24px;
        }

        .chart-header {
            margin-bottom: 24px;
        }

        .chart-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .chart-canvas {
            height: 300px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            padding-top: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .chart-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            height: 100%;
            justify-content: flex-end;
            position: relative;
        }

        .chart-bar {
            width: 100%;
            background: linear-gradient(180deg, #0f766e 0%, #2dd4bf 100%);
            border-radius: 4px 4px 0 0;
            opacity: 0.8;
            transition: all 0.3s;
        }

        .chart-col:hover .chart-bar {
            opacity: 1;
            transform: scaleX(1.1);
        }

        /* Tooltip on hover */
        .chart-col:hover::after {
            content: attr(data-label);
            position: absolute;
            top: -30px;
            background: #1e293b;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 10;
        }

        .chart-label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* --- 4. VERIFICATION TABLE --- */
        .table-card {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .finance-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 900px;
        }

        .finance-table th {
            background: #f8fafc;
            text-align: left;
            padding: 16px 24px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 700;
            border-bottom: 1px solid var(--border-color);
        }

        .finance-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        .finance-table tr:last-child td {
            border-bottom: none;
        }

        .finance-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pending { background: #fef9c3; color: #a16207; }
        .status-paid { background: #dcfce7; color: #047857; }
        .status-rejected { background: #fee2e2; color: #b91c1c; }
        .status-failed { background: #f1f5f9; color: #475569; }

        /* Action Buttons */
        .btn-group {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.1s;
        }

        .btn-approve { background: var(--primary); color: white; }
        .btn-approve:hover { background: #115e59; }

        .btn-reject { background: #fee2e2; color: #b91c1c; }
        .btn-reject:hover { background: #fecaca; }

        .btn-proof {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .btn-proof:hover { text-decoration: underline; }

        .empty-state {
            text-align: center;
            padding: 48px;
            color: var(--text-muted);
        }

        @media (max-width: 1024px) {
            .status-summary { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .status-summary { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        }
    </style>
@endpush

@section('content')
    <div class="finance-container">

        {{-- 1. Header --}}
        <div class="page-header">
            <div class="header-content">
                <h2>Manajemen Keuangan</h2>
                <p>Monitor arus kas, verifikasi pembayaran, dan kelola status transaksi siswa.</p>
            </div>
            {{-- Bisa tambah tombol export report di sini jika perlu --}}
        </div>

        {{-- 2. Stats Overview --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Total Pemasukan</span>
                    <div class="stat-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['totalRevenue'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Tahun Ini</span>
                    <div class="stat-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['yearRevenue'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Siswa Aktif</span>
                    <div class="stat-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($stats['totalStudents']) }}</div>
            </div>
        </div>

        {{-- 3. Status Summary --}}
        <div class="status-summary">
            <div class="status-box">
                <span class="bg-pending">Pending</span>
                <strong>{{ number_format($statusSummary['pending']['count']) }}</strong>
            </div>
            <div class="status-box">
                <span class="bg-paid">Paid</span>
                <strong>{{ number_format($statusSummary['paid']['count']) }}</strong>
            </div>
            <div class="status-box">
                <span class="bg-rejected">Rejected</span>
                <strong>{{ number_format($statusSummary['rejected']['count']) }}</strong>
            </div>
            <div class="status-box">
                <span class="bg-failed">Timeout</span>
                <strong>{{ number_format($statusSummary['failed']['count']) }}</strong>
            </div>
        </div>

        {{-- 4. Chart --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3>Grafik Pendapatan Bulanan {{ now()->year }}</h3>
            </div>
            @php $maxValue = max($monthlyRevenue->pluck('value')->all() ?: [1]); @endphp
            <div class="chart-canvas">
                @foreach ($monthlyRevenue as $entry)
                    @php $height = $maxValue > 0 ? max(($entry['value'] / $maxValue) * 100, 5) : 5; @endphp
                    <div class="chart-col" data-label="{{ $entry['label'] }}: {{ $entry['formatted_short'] ?? $entry['value'] }}">
                        <div class="chart-bar" style="height: {{ $height }}%;"></div>
                        <span class="chart-label">{{ $entry['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 5. Verification Table --}}
        <div class="table-card">
            <div class="table-header">
                <h3>Verifikasi Pembayaran Terbaru</h3>
            </div>
            <div class="table-responsive">
                <table class="finance-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Paket Belajar</th>
                            <th>Siswa</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td style="font-family: monospace;">#{{ substr($order['id'], 0, 8) }}</td>
                                <td style="font-weight: 600;">{{ $order['package'] }}</td>
                                <td>{{ $order['student'] }}</td>
                                <td>{{ $order['total'] }}</td>
                                <td>{{ $order['due_at'] }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($order['status']) {
                                            'paid' => 'status-paid',
                                            'pending' => 'status-pending',
                                            'rejected' => 'status-rejected',
                                            default => 'status-failed'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ $order['status_label'] }}</span>
                                </td>
                                <td>
                                    @if ($order['proof'])
                                        <a href="{{ $order['proof'] }}" target="_blank" class="btn-proof">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order['canApprove'] || $order['canReject'])
                                        <div class="btn-group">
                                            @if ($order['canApprove'])
                                                <form action="{{ route('admin.finance.approve', $order['id']) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-action btn-approve">Terima</button>
                                                </form>
                                            @endif
                                            @if ($order['canReject'])
                                                <form action="{{ route('admin.finance.reject', $order['id']) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-action btn-reject">Tolak</button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 0.8rem;">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <p>Belum ada transaksi pembayaran yang tercatat.</p>
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