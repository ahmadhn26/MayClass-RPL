@extends('admin.layout')

@section('title', 'Dashboard Admin - MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --primary-light: #ccfbf1;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-surface: #ffffff;
            --bg-body: #f1f5f9;
            --border-subtle: #e2e8f0;
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --radius-lg: 16px;
            --radius-md: 12px;
        }

        .dashboard-container {
            display: grid;
            gap: 32px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* --- 1. HERO SECTION --- */
        .hero-panel {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            padding: 32px;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 40px;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-subtle);
            align-items: center;
        }

        .hero-content h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 12px 0;
            letter-spacing: -0.02em;
        }

        .hero-content p {
            font-size: 1rem;
            color: var(--text-muted);
            line-height: 1.6;
            max-width: 600px;
            margin: 0;
        }

        .hero-stats-sidebar {
            background: #f8fafc;
            border-radius: var(--radius-md);
            padding: 20px;
            border: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .hero-trend-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .hero-trend-box label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }

        .hero-trend-box .amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
        }

        .trend-badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 99px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .trend-up { background: #dcfce7; color: #15803d; }
        .trend-down { background: #fee2e2; color: #b91c1c; }
        .trend-neutral { background: #f1f5f9; color: #64748b; }

        .hero-mini-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 8px;
            padding-top: 16px;
            border-top: 1px dashed var(--border-subtle);
        }

        .mini-stat-item strong {
            display: block;
            font-size: 1.1rem;
            color: var(--text-main);
        }
        .mini-stat-item span {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* --- 2. KPI CARDS --- */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
        }

        .kpi-card {
            background: var(--bg-surface);
            padding: 24px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: transform 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
        }

        .kpi-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .kpi-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .kpi-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 4px;
        }

        .kpi-desc {
            font-size: 0.85rem;
            color: #94a3b8;
        }

        /* --- 3. CHARTS & PIPELINE --- */
        .insights-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .section-card {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-card);
            padding: 28px;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .card-title h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-main);
        }

        .card-title span {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* CSS Chart */
        .chart-container {
            height: 250px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            padding-top: 20px;
        }

        .chart-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            height: 100%;
            justify-content: flex-end;
            group: hover;
        }

        .chart-bar {
            width: 100%;
            background: linear-gradient(180deg, #0f766e 0%, #2dd4bf 100%);
            border-radius: 6px 6px 2px 2px;
            opacity: 0.85;
            transition: height 0.4s ease, opacity 0.2s;
            position: relative;
            min-height: 4px;
        }

        .chart-col:hover .chart-bar {
            opacity: 1;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
        }

        /* Tooltip sederhana pakai CSS */
        .chart-bar::before {
            content: attr(data-value);
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            white-space: nowrap;
        }

        .chart-col:hover .chart-bar::before {
            opacity: 1;
        }

        .chart-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Pipeline List */
        .pipeline-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .pipeline-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .pipeline-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .pipeline-bar-bg {
            height: 8px;
            background: #f1f5f9;
            border-radius: 99px;
            overflow: hidden;
        }

        .pipeline-bar-fill {
            height: 100%;
            border-radius: 99px;
        }

        /* --- 4. TABLE & LISTS --- */
        .details-section {
            display: grid;
            grid-template-columns: 1.8fr 1.2fr;
            gap: 24px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.9rem;
        }

        .modern-table th {
            text-align: left;
            padding: 12px 16px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-subtle);
            letter-spacing: 0.03em;
        }

        .modern-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-main);
        }

        .modern-table tr:last-child td {
            border-bottom: none;
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }
        .status-failed { background: #fee2e2; color: #b91c1c; }
        .status-default { background: #f1f5f9; color: #475569; }

        .list-group {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .list-item:last-child { border-bottom: none; }

        .item-main strong { display: block; font-size: 0.95rem; color: var(--text-main); }
        .item-main span { font-size: 0.85rem; color: var(--text-muted); }
        .item-side { font-weight: 700; font-size: 0.95rem; color: var(--primary); }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--text-muted);
            font-size: 0.9rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px dashed var(--border-subtle);
        }

        @media (max-width: 1024px) {
            .hero-panel, .insights-section, .details-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
<div class="dashboard-container">

    {{-- 1. Hero Section --}}
    <section class="hero-panel">
        <div class="hero-content">
            <h2>Halo Admin, Selamat Datang!</h2>
            <p>
                Berikut adalah ringkasan performa platform MayClass hari ini. 
                Pantau pendapatan, pendaftaran siswa, dan status pembayaran secara real-time.
            </p>
        </div>
        
        <div class="hero-stats-sidebar">
            @php
                $direction = $monthComparison['direction'];
                $trendClass = $direction === 1 ? 'trend-up' : ($direction === -1 ? 'trend-down' : 'trend-neutral');
                $trendIcon = $direction === 1 ? '▲' : ($direction === -1 ? '▼' : '•');
            @endphp
            
            <div class="hero-trend-box">
                <div>
                    <label>Pendapatan Bulan Ini</label>
                    <div class="amount">{{ $monthComparison['formatted'] }}</div>
                </div>
                <div class="trend-badge {{ $trendClass }}">
                    {{ $trendIcon }} {{ number_format(abs($monthComparison['delta']), 1, ',', '.') }}%
                </div>
            </div>

            <div class="hero-mini-stats">
                <div class="mini-stat-item">
                    <strong>{{ number_format($stats['totalStudents']) }}</strong>
                    <span>Total Siswa</span>
                </div>
                <div class="mini-stat-item">
                    <strong>{{ number_format($stats['pendingPayments']) }}</strong>
                    <span>Menunggu Verifikasi</span>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. KPI Cards --}}
    <section class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-icon">Rp</div>
                <span>Tahun Ini</span>
            </div>
            <div class="kpi-value">{{ $stats['yearRevenue'] }}</div>
            <div class="kpi-desc">Total pendapatan bersih 2025</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-icon">Σ</div>
                <span>Total</span>
            </div>
            <div class="kpi-value">{{ $stats['totalRevenue'] }}</div>
            <div class="kpi-desc">Akumulasi sejak awal</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-icon">Ø</div>
                <span>Rata-rata</span>
            </div>
            <div class="kpi-value">{{ $stats['averageTicket'] }}</div>
            <div class="kpi-desc">Per transaksi sukses</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-icon">✓</div>
                <span>Transaksi</span>
            </div>
            <div class="kpi-value">{{ number_format($stats['paidOrders']) }}</div>
            <div class="kpi-desc">Pembayaran berhasil</div>
        </div>
    </section>

    {{-- 3. Charts & Pipeline --}}
    <section class="insights-section">
        {{-- Chart --}}
        <div class="section-card">
            <div class="card-title">
                <h3>Grafik Pendapatan</h3>
                <span>{{ now()->year }}</span>
            </div>

            @if ($monthlyRevenue->sum('value') === 0)
                <div class="empty-state">Belum ada data transaksi untuk ditampilkan</div>
            @else
                @php $maxValue = max($monthlyRevenue->pluck('value')->all() ?: [1]); @endphp
                <div class="chart-container">
                    @foreach ($monthlyRevenue as $entry)
                        @php $h = $maxValue > 0 ? max(($entry['value'] / $maxValue) * 100, 5) : 5; @endphp
                        <div class="chart-col">
                            {{-- Tooltip data-value added --}}
                            <div class="chart-bar" style="height: {{ $h }}%;" data-value="{{ $entry['formatted'] }}"></div>
                            <span class="chart-label">{{ $entry['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pipeline --}}
        <div class="section-card">
            <div class="card-title">
                <h3>Status Pembayaran</h3>
                <span>Distribusi Order</span>
            </div>

            @if ($paymentPipeline['total'] === 0)
                <div class="empty-state">Data tidak tersedia</div>
            @else
                <div class="pipeline-list">
                    @foreach ($paymentPipeline['rows'] as $row)
                        @php
                            $color = match($row['status']) {
                                'paid' => '#22c55e',
                                'pending' => '#eab308',
                                'failed' => '#ef4444',
                                default => '#3b82f6'
                            };
                        @endphp
                        <div class="pipeline-item">
                            <div class="pipeline-info">
                                <span>{{ $row['label'] }}</span>
                                <span>{{ number_format($row['count']) }} ({{ $row['percentage'] }}%)</span>
                            </div>
                            <div class="pipeline-bar-bg">
                                <div class="pipeline-bar-fill" style="width: {{ $row['percentage'] }}%; background: {{ $color }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- 4. Tables & Lists --}}
    <section class="details-section">
        {{-- Recent Transactions --}}
        <div class="section-card">
            <div class="card-title">
                <h3>Transaksi Terbaru</h3>
                <a href="#" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">Lihat Semua &rarr;</a>
            </div>

            @if ($recentPayments->isEmpty())
                <div class="empty-state">Belum ada transaksi</div>
            @else
                <div class="table-wrapper">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Siswa</th>
                                <th>Paket</th>
                                <th>Status</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentPayments as $payment)
                                @php
                                    $badgeClass = match($payment['status']) {
                                        'paid' => 'status-paid',
                                        'pending' => 'status-pending',
                                        'failed' => 'status-failed',
                                        default => 'status-default'
                                    };
                                @endphp
                                <tr>
                                    <td style="font-family: monospace;">{{ $payment['invoice'] }}</td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $payment['student'] }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $payment['paid_at'] }}</div>
                                    </td>
                                    <td>{{ $payment['package'] }}</td>
                                    <td><span class="status-pill {{ $badgeClass }}">{{ $payment['status_label'] }}</span></td>
                                    <td style="text-align: right; font-weight: 700;">{{ $payment['total'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Top Packages & Students --}}
        <div style="display: grid; gap: 24px;">
            {{-- Top Packages --}}
            <div class="section-card">
                <div class="card-title">
                    <h3>Paket Terpopuler</h3>
                </div>
                @if ($topPackages->isEmpty())
                    <div class="empty-state">Data belum cukup</div>
                @else
                    <div class="list-group">
                        @foreach ($topPackages as $package)
                            <div class="list-item">
                                <div class="item-main">
                                    <strong>{{ $package['title'] }}</strong>
                                    <span>{{ number_format($package['orders']) }} Terjual</span>
                                </div>
                                <div class="item-side">{{ $package['revenue'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent Students --}}
            <div class="section-card">
                <div class="card-title">
                    <h3>Siswa Baru</h3>
                    <a href="{{ route('admin.students.index') }}" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">Semua Siswa</a>
                </div>
                @if ($recentStudents->isEmpty())
                    <div class="empty-state">Belum ada pendaftaran</div>
                @else
                    <div class="list-group">
                        @foreach ($recentStudents as $student)
                            <div class="list-item">
                                <div class="item-main">
                                    <strong>{{ $student['name'] }}</strong>
                                    <span>{{ $student['email'] }}</span>
                                </div>
                                <div class="item-side" style="font-weight: 400; font-size: 0.8rem; color: var(--text-muted);">
                                    {{ $student['joined_at'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

</div>
@endsection