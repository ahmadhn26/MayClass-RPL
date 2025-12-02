@extends('admin.layout')

@section('title', 'Metode Pembayaran - MayClass')

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
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

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

        .payment-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.92rem;
            min-width: 800px;
        }

        .payment-table th {
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

        .payment-table th:last-child {
            text-align: right;
            padding-right: 24px;
        }

        .payment-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        .payment-table td:last-child {
            text-align: right;
            padding-right: 24px;
        }

        .payment-table tr:last-child td {
            border-bottom: none;
        }

        .payment-table tbody tr {
            transition: background 0.2s;
        }

        .payment-table tbody tr:hover {
            background: #fcfcfc;
        }

        .type-badge {
            display: inline-block;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .type-badge.bank {
            background: #e0f2fe;
            color: #0369a1;
            border-color: #bae6fd;
        }

        .type-badge.ewallet {
            background: #dcfce7;
            color: #15803d;
            border-color: #bbf7d0;
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

        .action-group {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
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
            background: #ffffff;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
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
            background: white;
            font-family: inherit;
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

        .radio-group {
            display: flex;
            gap: 16px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .radio-option:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .radio-option input {
            accent-color: var(--primary);
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

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }

        .checkbox-wrapper input {
            accent-color: var(--primary);
            width: 18px;
            height: 18px;
        }

        /* Field Visibility */
        .bank-field {
            display: none;
        }

        .bank-field.show {
            display: flex;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                max-width: 95%;
                margin: 0 auto;
            }

            .modal-header {
                padding: 20px 24px;
            }

            .modal-header h2 {
                font-size: 1.25rem;
            }

            .modal-body {
                padding: 24px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .radio-group {
                flex-direction: column;
                gap: 12px;
            }

            .radio-option {
                width: 100%;
            }

            .modal-footer {
                padding: 20px 24px;
                flex-direction: column-reverse;
            }

            .btn-cancel,
            .btn-submit {
                width: 100%;
            }

            .btn-submit {
                margin-top: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-container">

        {{-- Header Card --}}
        <div class="header-card">
            <div class="header-content">
                <h1>Metode Pembayaran</h1>
                <p>Kelola metode pembayaran yang tersedia untuk siswa.</p>
            </div>
            <button type="button" class="btn-add" onclick="openModal('addModal')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Metode Pembayaran
            </button>
        </div>

        {{-- Table Card --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <th>Type</th>
                            <th>Nomor Akun</th>
                            <th>Atas Nama</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($methods as $method)
                            <tr>
                                <td>
                                    <strong>{{ $method->name }}</strong>
                                    @if($method->type === 'bank' && $method->bank_name)
                                        <br><small style="color: var(--text-muted);">{{ $method->bank_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="type-badge {{ $method->type }}">
                                        {{ $method->getTypeLabel() }}
                                    </span>
                                </td>
                                <td>{{ $method->account_number }}</td>
                                <td>{{ $method->account_holder }}</td>
                                <td>
                                    <span class="status-pill {{ $method->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $method->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button type="button" class="btn-action btn-edit"
                                            onclick="openEditModal({{ $method->id }}, '{{ $method->name }}', '{{ $method->type }}', '{{ $method->bank_name }}', '{{ $method->account_number }}', '{{ $method->account_holder }}', {{ $method->is_active ? 'true' : 'false' }})">
                                            Edit
                                        </button>
                                        <button type="button" class="btn-delete" data-id="{{ $method->id }}"
                                            data-name="{{ $method->name }}"
                                            data-action="{{ route('admin.payment-methods.destroy', $method->id) }}">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <td colspan="6">
                                    <div class="empty-state">
                                        <p>Belum ada metode pembayaran yang ditambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ADD MODAL --}}
    <div id="addModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Metode Pembayaran</h2>
                <button type="button" class="btn-close" onclick="closeModal('addModal')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.payment-methods.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Nama Metode <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                placeholder="Contoh: ShopeePay" required>
                            <p class="helper-text">Nama yang akan ditampilkan kepada siswa</p>
                        </div>

                        <div class="form-group full-width">
                            <label>Tipe Metode <span style="color: #ef4444;">*</span></label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" name="type" value="bank" id="type-bank-add"
                                        onchange="toggleBankField('add')">
                                    <span>Bank Transfer</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="type" value="ewallet" id="type-ewallet-add" checked
                                        onchange="toggleBankField('add')">
                                    <span>E-Wallet</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group full-width bank-field" id="bank-name-field-add">
                            <label>Nama Bank <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}"
                                placeholder="Contoh: BCA, Mandiri">
                        </div>

                        <div class="form-group">
                            <label>Nomor Rekening/HP <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="account_number" class="form-control"
                                value="{{ old('account_number') }}" placeholder="Contoh: 081234567890" required>
                        </div>

                        <div class="form-group">
                            <label>Atas Nama <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="account_holder" class="form-control"
                                value="{{ old('account_holder') }}" placeholder="Contoh: Maylina" required>
                        </div>

                        <div class="form-group full-width">
                            <label>&nbsp;</label>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="is_active" value="1" id="is_active_add" checked>
                                <label for="is_active_add" style="margin: 0;">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Metode Pembayaran</h2>
                <button type="button" class="btn-close" onclick="closeModal('editModal')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Nama Metode <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="form-group full-width">
                            <label>Tipe Metode <span style="color: #ef4444;">*</span></label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" name="type" value="bank" id="type-bank-edit"
                                        onchange="toggleBankField('edit')">
                                    <span>Bank Transfer</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="type" value="ewallet" id="type-ewallet-edit"
                                        onchange="toggleBankField('edit')">
                                    <span>E-Wallet</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group full-width bank-field" id="bank-name-field-edit">
                            <label>Nama Bank <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="bank_name" id="edit_bank_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Nomor Rekening/HP <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="account_number" id="edit_account_number" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Atas Nama <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="account_holder" id="edit_account_holder" class="form-control" required>
                        </div>

                        <div class="form-group full-width">
                            <label>&nbsp;</label>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="is_active" value="1" id="edit_is_active">
                                <label for="edit_is_active" style="margin: 0;">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn-submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = '';
        }

        function toggleBankField(mode) {
            const bankField = document.getElementById('bank-name-field-' + mode);
            const typeBank = document.getElementById('type-bank-' + mode);

            if (typeBank.checked) {
                bankField.classList.add('show');
            } else {
                bankField.classList.remove('show');
            }
        }

        function openEditModal(id, name, type, bankName, accountNumber, accountHolder, isActive) {
            const form = document.getElementById('editForm');
            form.action = `/admin/payment-methods/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_account_number').value = accountNumber;
            document.getElementById('edit_account_holder').value = accountHolder;
            document.getElementById('edit_bank_name').value = bankName || '';
            document.getElementById('edit_is_active').checked = isActive;

            if (type === 'bank') {
                document.getElementById('type-bank-edit').checked = true;
            } else {
                document.getElementById('type-ewallet-edit').checked = true;
            }

            toggleBankField('edit');
            openModal('editModal');
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const activeModals = document.querySelectorAll('.modal-overlay.active');
                activeModals.forEach(modal => {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
        });
    </script>
@endpush