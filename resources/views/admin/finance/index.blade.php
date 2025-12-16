@extends('admin.layout')

@section('title', 'Manajemen Keuangan - MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --primary-hover: #0d9488;
            --primary-light: #ccfbf1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-surface: #ffffff;
            --bg-body: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        /* --- LAYOUT UTILS --- */
        .finance-container {
            display: flex;
            flex-direction: column;
            gap: 32px;
            max-width: 1600px;
            margin: 0 auto;
            padding-bottom: 40px;
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
            letter-spacing: -0.025em;
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
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--bg-body);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .stat-value {
            font-size: 1.85rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.025em;
        }

        /* --- 3. STATUS SUMMARY --- */
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
            gap: 8px;
            transition: background 0.2s;
        }

        .status-box strong {
            font-size: 1.5rem;
            color: var(--text-main);
            font-weight: 700;
            line-height: 1;
        }

        .status-label-pill {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 99px;
            letter-spacing: 0.05em;
        }

        .pill-pending {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .pill-paid {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .pill-rejected {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .pill-failed {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* --- 5. MODERN TABLE STYLES --- */
        .table-card {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .table-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border-color);
            background: #fff;
        }

        .table-header h3 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            /* Allows for cleaner row spacing visuals */
            border-spacing: 0;
            min-width: 1000px;
        }

        .modern-table th {
            background: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px 24px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .modern-table td {
            padding: 20px 24px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            font-size: 0.9rem;
            transition: background 0.15s;
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        .modern-table tbody tr:hover td {
            background-color: #f8fafc;
        }

        /* Table Column Specifics */
        .col-invoice {
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            color: var(--primary);
            font-weight: 500;
            font-size: 0.85rem;
        }

        .col-package {
            font-weight: 600;
            color: var(--text-main);
        }

        .col-total {
            font-weight: 700;
        }

        .col-date {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Student Profile in Table */
        .student-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .student-name {
            font-weight: 500;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: currentColor;
        }

        /* Dropdown Styles */
        .dropdown-wrapper {
            position: relative;
            display: inline-block;
        }

        .btn-action-dropdown {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            border-radius: 8px;
            /* Slightly rounder */
            font-size: 0.875rem;
            font-weight: 500;
            color: #334155;
            /* Slate-700 */
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            /* Subtle button shadow */
        }

        .btn-action-dropdown:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
            color: #0f172a;
            transform: translateY(-1px);
        }

        .btn-action-dropdown:active {
            transform: translateY(0);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            /* Space it out a bit */
            background: white;
            border: 1px solid #f1f5f9;
            /* Very subtle border */
            border-radius: 12px;
            /* Smooth corners */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            /* Deep classy shadow */
            min-width: 180px;
            z-index: 100;
            padding: 6px;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
        }

        .dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .dropdown-item {
            width: 100%;
            border: none;
            background: transparent;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 8px;
            transition: all 0.15s ease;
            margin-bottom: 2px;
        }

        .dropdown-item:last-child {
            margin-bottom: 0;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        /* Specific Item Colors on Hover - keeping base color neutral for cleanliness, accent on hover/icon */
        .item-approve {
            color: #059669;
        }

        .item-approve:hover {
            background-color: #ecfdf5;
            color: #047857;
        }

        .item-refund {
            color: #d97706;
        }

        .item-refund:hover {
            background-color: #fffbeb;
            color: #b45309;
        }

        .item-reject {
            color: #dc2626;
        }

        .item-reject:hover {
            background-color: #fef2f2;
            color: #b91c1c;
        }

        .status-finished {
            color: #94a3b8;
            font-size: 0.85rem;
            font-style: italic;
            font-weight: 500;
        }

        .status-pending {
            background: #fffbeb;
            color: #b45309;
        }

        .status-paid {
            background: #ecfdf5;
            color: #047857;
        }

        .status-rejected {
            background: #fef2f2;
            color: #b91c1c;
        }

        .status-failed {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-checkout {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        /* Action Buttons */
        .action-cell {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-action-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-check {
            background: #dcfce7;
            color: #047857;
        }

        .btn-check:hover {
            background: #059669;
            color: white;
        }

        .btn-cross {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-cross:hover {
            background: #dc2626;
            color: white;
        }

        .btn-view-proof {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-view-proof:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--bg-body);
        }

        .empty-state {
            padding: 64px;
            text-align: center;
            color: var(--text-muted);
        }

        /* === MODAL STYLES (Keep existing but refined) === */
        .payment-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            animation: fadeIn 0.2s ease;
        }

        .modal-overlay {
            position: absolute;
            background: rgba(15, 23, 42, 0.6);
            width: 100%;
            height: 100%;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            position: relative;
            background: var(--bg-surface);
            max-width: 650px;
            margin: 40px auto;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideDown 0.3s ease;
            max-height: calc(100vh - 80px);
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 32px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-body {
            padding: 32px;
        }

        .modal-footer {
            padding: 20px 32px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            background: #f8fafc;
            border-radius: 0 0 16px 16px;
        }

        /* ...Keep other modal styles for grid... */
        .payment-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .status-summary {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .status-summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .modal-content {
                width: 95%;
                margin: 10px auto;
            }

            .payment-info-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
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
        </div>

        {{-- 2. Stats Overview --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Total Pemasukan</span>
                    <div class="stat-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['totalRevenue'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Tahun Ini</span>
                    <div class="stat-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['yearRevenue'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Siswa Aktif</span>
                    <div class="stat-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($stats['totalStudents']) }}</div>
            </div>
        </div>

        {{-- 3. Status Summary --}}
        <div class="status-summary">
            <div class="status-box">
                <span class="status-label-pill pill-pending">Pending</span>
                <strong>{{ number_format($statusSummary['pending']['count']) }}</strong>
            </div>
            <div class="status-box">
                <span class="status-label-pill pill-paid">Paid</span>
                <strong>{{ number_format($statusSummary['paid']['count']) }}</strong>
            </div>
            <div class="status-box">
                <span class="status-label-pill pill-rejected">Rejected</span>
                <strong>{{ number_format($statusSummary['rejected']['count']) }}</strong>
            </div>
            <div class="status-box">
                <span class="status-label-pill pill-failed">Timeout</span>
                <strong>{{ number_format($statusSummary['failed']['count']) }}</strong>
            </div>
        </div>

        {{-- 5. Verification Table (MODERN REDESIGN) --}}
        <div class="table-card">
            <div class="table-header">
                <h3>Verifikasi Pembayaran Terbaru</h3>
            </div>
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th width="15%">Invoice ID</th>
                            <th width="20%">Siswa</th>
                            <th width="20%">Paket Belajar</th>
                            <th width="15%">Total</th>
                            <th width="10%">Status</th>
                            <th width="10%">Bukti</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="col-invoice">#{{ substr($order['id'], 0, 8) }}</td>

                                <td>
                                    <div class="student-cell">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($order['student'], 0, 1)) }}
                                        </div>
                                        <div style="display: flex; flex-direction: column;">
                                            <span class="student-name">{{ $order['student'] }}</span>
                                            <span class="col-date">{{ $order['due_at'] }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="col-package">{{ $order['package'] }}</td>

                                <td class="col-total">{{ $order['total'] }}</td>

                                <td>
                                    <span class="status-badge {{ $order['status_class'] }}">
                                        <span class="status-dot"></span>
                                        {{ $order['status_label'] }}
                                    </span>
                                </td>

                                <td>
                                    @if ($order['proof'])
                                        <button type="button" class="btn-view-proof" onclick="openPaymentModal({{ $order['id'] }})">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </button>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($order['canApprove'] || $order['canReject'])
                                        <div class="dropdown-wrapper">
                                            <button type="button" class="btn-action-dropdown"
                                                onclick="toggleDropdown({{ $order['id'] }})">
                                                Aksi
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>

                                            <div id="dropdown-{{ $order['id'] }}" class="dropdown-menu">
                                                @if ($order['canApprove'])
                                                    <form action="{{ route('admin.finance.approve', $order['id']) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item item-approve">
                                                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Setujui
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($order['canReject'])
                                                    <form action="{{ route('admin.finance.refund', $order['id']) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item item-refund"
                                                            onclick="return confirm('Apakah Anda yakin ingin melakukan refund dana untuk pesanan ini?')">
                                                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                                </path>
                                                            </svg>
                                                            Refund
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.finance.reject', $order['id']) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item item-reject"
                                                            onclick="return confirm('Tolak bukti pembayaran ini?')">
                                                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Tolak
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="status-finished">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <svg style="margin-bottom: 16px; color: var(--border-color);" width="48" height="48"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                        <p>Belum ada transaksi pembayaran yang tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Payment Detail Modal --}}
        @foreach ($orders as $order)
            <div class="payment-modal" id="modal-{{ $order['id'] }}" style="display: none;">
                <div class="modal-overlay" onclick="closePaymentModal({{ $order['id'] }})"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Detail Pembayaran</h3>
                        <button type="button" class="btn-close-modal" style="background:none; border:none; cursor:pointer;"
                            onclick="closePaymentModal({{ $order['id'] }})">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="payment-info-grid">
                            <div class="info-item">
                                <span class="info-label"
                                    style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Informasi
                                    Siswa</span>
                                <div style="display:flex; flex-direction:column; gap:8px;">
                                    <div>
                                        <span class="info-value"
                                            style="font-weight:600; display:block;">{{ $order['student'] }}</span>
                                        <span
                                            style="font-size: 0.85rem; color: #64748b; display: flex; align-items: center; gap: 4px; margin-top:2px;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            {{ $order['student_phone'] }}
                                        </span>
                                    </div>

                                    @if($order['student_phone'] !== '-')
                                        @php
                                            $phone = preg_replace('/[^0-9]/', '', $order['student_phone']);
                                            if (str_starts_with($phone, '0')) {
                                                $phone = '62' . substr($phone, 1);
                                            }
                                        @endphp
                                        <a href="https://wa.me/{{ $phone }}?text=Halo%20{{ urlencode($order['student']) }},%20saya%20admin%20MayClass.%20Terkait%20pembayaran%20paket%20belajar%20Anda..."
                                            target="_blank"
                                            style="display:inline-flex; align-items:center; gap:6px; background:#25D366; color:white; padding:6px 12px; border-radius:6px; font-size:0.75rem; font-weight:600; text-decoration:none; width:fit-content; transition:background 0.2s;">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.592 2.654-.698c1.005.572 1.903.87 3.05.87 3.183 0 5.768-2.586 5.768-5.766.001-3.181-2.585-5.766-5.766-5.766zm-5.768 5.766c.001-3.181 2.586-5.767 5.766-5.767 3.183 0 5.768 2.586 5.768 5.767-.001 3.181-2.585 5.767-5.766 5.767-3.183 0-5.768-2.586-5.768-5.767z">
                                                </path>
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                            </svg>
                                            Hubungi via WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <span class="info-label"
                                    style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Paket</span>
                                <span class="info-value" style="font-weight:600;">{{ $order['package'] }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"
                                    style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Metode</span>
                                <span class="info-value">
                                    @php
                                        $methodLabel = match ($order['payment_method']) {
                                            'transfer_bank' => 'Transfer Bank',
                                            'shopeepay' => 'ShopeePay',
                                            'gopay' => 'GoPay',
                                            'ovo' => 'OVO',
                                            'dana' => 'DANA',
                                            default => $order['payment_method'] ?? '-'
                                        };
                                    @endphp
                                    {{ $methodLabel }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"
                                    style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Total</span>
                                <span class="info-value"
                                    style="color:var(--primary); font-weight:700; font-size:1.1rem;">{{ $order['total'] }}</span>
                            </div>
                        </div>

                        @if ($order['proof'])
                            <div class="proof-preview">
                                <span
                                    style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600; display:block; margin-bottom:8px;">Bukti
                                    Pembayaran</span>
                                <div style="border-radius:12px; overflow:hidden; border:1px solid #e2e8f0;">
                                    <img src="{{ $order['proof'] }}" alt="Bukti Pembayaran" style="width:100%; display:block;">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-close"
                            style="background:var(--primary); color:white; border:none; padding:10px 24px; border-radius:8px; font-weight:600; cursor:pointer;"
                            onclick="closePaymentModal({{ $order['id'] }})">Tutup</button>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Dropdown Logic ---
            window.toggleDropdown = function (id) {
                // Close all other dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(el => {
                    if (el.id !== 'dropdown-' + id) el.classList.remove('show');
                });
                const dropdown = document.getElementById('dropdown-' + id);
                if (dropdown) {
                    dropdown.classList.toggle('show');
                }
            };

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.dropdown-wrapper')) {
                    document.querySelectorAll('.dropdown-menu').forEach(el => el.classList.remove('show'));
                }
            });

            // --- Revenue Chart (Area) ---
            const revenueData = @json($monthlyRevenue);
            const revenueOptions = {
                series: [{
                    name: 'Pendapatan',
                    data: revenueData.map(item => item.value)
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    fontFamily: 'Poppins, sans-serif'
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: {
                    categories: revenueData.map(item => item.label),
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 90, 100]
                    }
                },
                colors: ['#0f766e'],
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            };

            if (document.querySelector("#revenueChart")) {
                const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
                revenueChart.render();
            }

            // --- Package Chart (Donut) ---
            const packageData = @json($packageDistribution);
            if (packageData.length > 0) {
                const packageOptions = {
                    series: packageData.map(item => item.value),
                    chart: {
                        type: 'donut',
                        height: 320,
                        fontFamily: 'Poppins, sans-serif'
                    },
                    labels: packageData.map(item => item.label),
                    colors: ['#0f766e', '#2dd4bf', '#f59e0b', '#ef4444', '#3b82f6'],
                    legend: {
                        position: 'bottom'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    }
                };

                if (document.querySelector("#packageChart")) {
                    const packageChart = new ApexCharts(document.querySelector("#packageChart"), packageOptions);
                    packageChart.render();
                }
            }
        });

        // --- Modal Controls ---
        function openPaymentModal(orderId) {
            const modal = document.getElementById('modal-' + orderId);
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        function closePaymentModal(orderId) {
            const modal = document.getElementById('modal-' + orderId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.payment-modal').forEach(modal => {
                    modal.style.display = 'none';
                });
                document.body.style.overflow = 'auto';
            }
        });
    </script>
@endpush