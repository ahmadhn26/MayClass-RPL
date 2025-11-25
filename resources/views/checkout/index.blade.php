<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        {{-- Gunakan null coalescing operator (??) untuk mencegah error undefined index --}}
        <title>Checkout - {{ $package['detail_title'] ?? 'Paket Belajar' }}</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />
        <style>
            :root {
                --primary: #0f766e;
                --primary-hover: #115e59;
                --primary-light: #f0fdfa;
                --accent: #f59e0b;
                --danger: #ef4444;
                --surface: #ffffff;
                --bg-body: #f8fafc;
                --text-main: #0f172a;
                --text-muted: #64748b;
                --border: #e2e8f0;
                --radius: 16px;
                --shadow-sm: 0 1px 3px 0 rgba(0,0,0,0.1);
                --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-body);
                color: var(--text-main);
                line-height: 1.6;
            }

            a { text-decoration: none; color: inherit; transition: all 0.2s; }

            /* Layout */
            .checkout-container {
                max-width: 1100px;
                margin: 40px auto;
                padding: 0 24px;
                display: grid;
                grid-template-columns: 1fr 380px;
                gap: 40px;
                align-items: start;
            }

            /* Header Nav */
            .nav-header {
                grid-column: 1 / -1;
                margin-bottom: 16px;
            }
            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: var(--text-muted);
                font-weight: 600;
                font-size: 0.9rem;
                padding: 8px 0;
            }
            .back-link:hover { color: var(--primary); transform: translateX(-4px); }

            /* Card Base */
            .card {
                background: var(--surface);
                border-radius: var(--radius);
                border: 1px solid var(--border);
                box-shadow: var(--shadow-sm);
                padding: 32px;
            }

            /* Header Content */
            .checkout-header h1 {
                font-size: 1.8rem;
                font-weight: 800;
                margin: 0 0 8px;
                color: var(--text-main);
            }
            .checkout-header p {
                color: var(--text-muted);
                font-size: 1rem;
                margin: 0 0 24px;
            }

            .order-id-badge {
                display: inline-block;
                background: var(--bg-body);
                border: 1px solid var(--border);
                padding: 6px 12px;
                border-radius: 8px;
                font-family: monospace;
                font-size: 0.9rem;
                color: var(--text-muted);
                margin-bottom: 24px;
            }

            /* Timer */
            .timer-box {
                background: #fffbeb;
                border: 1px solid #fcd34d;
                border-radius: 12px;
                padding: 16px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 32px;
                color: #92400e;
            }
            .timer-box strong { font-size: 1.5rem; font-weight: 800; font-variant-numeric: tabular-nums; }
            .timer-box[data-status='expired'] { background: #fef2f2; border-color: #fecaca; color: #991b1b; }

            /* Form Styles */
            .form-group { margin-bottom: 24px; }
            .form-label {
                display: block;
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 8px;
                color: var(--text-main);
            }
            
            .form-input {
                width: 100%;
                padding: 12px 16px;
                border: 1px solid var(--border);
                border-radius: 10px;
                font-size: 1rem;
                transition: all 0.2s;
                background: var(--surface);
            }
            .form-input:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            }

            /* Bank Info */
            .bank-details {
                background: var(--primary-light);
                border: 1px solid rgba(15, 118, 110, 0.15);
                border-radius: 12px;
                padding: 20px;
                margin-bottom: 24px;
            }
            .bank-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 12px;
                font-size: 0.95rem;
            }
            .bank-row:last-child { margin-bottom: 0; }
            .bank-label { color: var(--text-muted); }
            .bank-value { font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }
            
            .btn-copy {
                background: white;
                border: 1px solid rgba(15, 118, 110, 0.2);
                border-radius: 6px;
                padding: 4px 8px;
                cursor: pointer;
                color: var(--primary);
                transition: all 0.2s;
                display: flex; align-items: center;
            }
            .btn-copy:hover { background: var(--primary); color: white; }
            .btn-copy.copied { background: var(--primary); color: white; border-color: var(--primary); }

            /* === REDESIGNED UPLOAD AREA (CLEANER) === */
            .upload-wrapper {
                position: relative;
            }

            /* Tampilan Dropzone */
            .upload-zone {
                border: 2px dashed var(--border);
                border-radius: 16px; /* Lebih rounded */
                padding: 48px 24px;  /* Lebih luas vertikal */
                text-align: center;
                cursor: pointer;
                transition: all 0.2s ease;
                background: var(--bg-body);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 16px;
            }

            .upload-zone:hover {
                border-color: var(--primary);
                background: var(--primary-light);
            }

            /* Icon Cloud Upload */
            .upload-icon-circle {
                width: 64px;
                height: 64px;
                background: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid var(--border);
                margin-bottom: 4px;
                color: var(--primary);
                transition: transform 0.2s;
            }
            .upload-zone:hover .upload-icon-circle {
                transform: scale(1.1);
                border-color: var(--primary);
            }

            .upload-text-main {
                font-weight: 700;
                color: var(--text-main);
                font-size: 1.05rem;
            }

            .upload-text-sub {
                color: var(--text-muted);
                font-size: 0.85rem;
            }

            /* Tampilan File Preview */
            .file-preview-card {
                display: none; /* Hidden by default */
                background: white;
                border: 1px solid var(--border);
                border-radius: 12px;
                padding: 16px;
                align-items: center;
                justify-content: space-between;
                box-shadow: var(--shadow-sm);
                margin-top: 12px;
                animation: fadeIn 0.3s ease;
            }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
            
            .file-info {
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .file-icon-preview {
                width: 48px; height: 48px;
                background: var(--primary-light);
                color: var(--primary);
                border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.5rem;
            }

            .file-details {
                display: flex;
                flex-direction: column;
            }

            .file-name {
                font-weight: 600;
                font-size: 0.95rem;
                color: var(--text-main);
                max-width: 220px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .file-status {
                font-size: 0.8rem;
                color: #10b981; /* Green */
                font-weight: 500;
            }

            .btn-remove-file {
                background: transparent;
                border: none;
                color: var(--text-muted);
                cursor: pointer;
                padding: 8px;
                border-radius: 8px;
                transition: all 0.2s;
            }
            .btn-remove-file:hover {
                background: #fef2f2;
                color: var(--danger);
            }

            /* === END REDESIGN UPLOAD === */

            .btn-submit {
                width: 100%;
                padding: 16px;
                background: var(--primary);
                color: white;
                border: none;
                border-radius: 99px;
                font-size: 1.05rem;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.2s;
                margin-top: 24px;
                box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
            }
            .btn-submit:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15, 118, 110, 0.4); }
            .btn-submit:disabled { background: var(--text-muted); cursor: not-allowed; transform: none; box-shadow: none; }

            /* Sidebar Summary */
            .summary-title { font-size: 1.2rem; font-weight: 700; margin: 0 0 24px; }
            
            .summary-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 14px;
                font-size: 0.95rem;
                color: var(--text-muted);
            }
            .summary-item.total {
                border-top: 1px solid var(--border);
                padding-top: 18px;
                margin-top: 18px;
                font-weight: 700;
                color: var(--text-main);
                font-size: 1.15rem;
            }

            .pkg-detail {
                display: flex;
                gap: 16px;
                margin-bottom: 24px;
                padding-bottom: 24px;
                border-bottom: 1px solid var(--border);
                align-items: center;
            }
            .pkg-thumb {
                width: 56px; height: 56px;
                background: var(--primary-light);
                border-radius: 12px;
                display: flex; align-items: center; justify-content: center;
                color: var(--primary);
                font-size: 1.6rem;
            }

            /* Error Alert */
            .alert-error {
                background: #fef2f2;
                border: 1px solid #fecaca;
                color: #991b1b;
                padding: 14px;
                border-radius: 12px;
                margin-bottom: 24px;
                font-size: 0.9rem;
            }
            .input-error-msg { color: var(--danger); font-size: 0.85rem; margin-top: 6px; }

            @media (max-width: 900px) {
                .checkout-container { grid-template-columns: 1fr; }
                .summary-card { order: -1; }
            }
        </style>
    </head>
    <body>

        {{-- PREPARE DATA (SAFE MODE) --}}
        @php
            $pkg = $package ?? [];
            $pkgSlug = $pkg['slug'] ?? '';
            $pkgTitle = $pkg['detail_title'] ?? 'Paket Belajar';
            $pkgStage = $pkg['stage_label'] ?? $pkg['stage'] ?? 'Program';
            $pkgGrade = $pkg['grade_range'] ?? '';
            $pkgPriceNum = $pkg['price_numeric'] ?? 0;

            $ord = $order ?? null;
            $ordId = optional($ord)->id;
            $ordCode = $ordId ? 'MC-' . str_pad((string) $ordId, 6, '0', STR_PAD_LEFT) : '—';
            
            $subtotal = optional($ord)->subtotal ?? $pkgPriceNum;
            $total = optional($ord)->total ?? $subtotal;

            // Countdown default 24 jam jika tidak ada data
            $seconds = $countdownSeconds ?? (24 * 60 * 60);
            $countdownLabel = sprintf('%02d:%02d', floor($seconds / 60), $seconds % 60);
            
            // Expire Route
            $expireRoute = ($pkgSlug && $ordId) ? route('checkout.expire', [$pkgSlug, $ordId]) : '';
            
            // WhatsApp Data
            $adminNumber = '6281234567890'; // Ganti dengan nomor asli
            $waMessage = "Hai Admin MayClass, saya ingin bertanya mengenai pembayaran untuk paket: *$pkgTitle*. Mohon bantuannya.";
            $waLink = "https://wa.me/$adminNumber?text=" . urlencode($waMessage);
        @endphp

        <div class="checkout-container">
            <div class="nav-header">
                <a href="{{ $pkgSlug ? route('packages.show', $pkgSlug) : '#' }}" class="back-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Kembali ke Detail Paket
                </a>
            </div>

            <main class="card">
                <div class="checkout-header">
                    <h1>Checkout</h1>
                    <p>Selesaikan pembayaran untuk mengaktifkan paket belajar.</p>
                    <span class="order-id-badge">ID Pesanan: {{ $ordCode }}</span>
                </div>

                @if (session('checkout_expired'))
                    <div class="alert-error">Sesi checkout sebelumnya telah berakhir. Silakan lakukan pembayaran ulang.</div>
                @endif

                <div class="timer-box" 
                     data-countdown 
                     data-remaining="{{ $seconds }}" 
                     data-expire-endpoint="{{ $expireRoute }}">
                    <div>
                        <span style="display:block; font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">Sisa Waktu Pembayaran</span>
                        <span style="font-size:0.9rem; opacity:0.9;">Segera transfer sebelum waktu habis.</span>
                    </div>
                    <strong data-countdown-display>{{ $countdownLabel }}</strong>
                </div>

                <div class="bank-details">
                    <div class="bank-row">
                        <span class="bank-label">Bank Tujuan</span>
                        <span class="bank-value">BCA</span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">Nomor Rekening</span>
                        <span class="bank-value">
                            <span id="acc-num">1234 5678 90</span>
                            <button type="button" class="btn-copy" data-target="#acc-num" title="Salin">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            </button>
                        </span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">Atas Nama</span>
                        <span class="bank-value">Maylina</span>
                    </div>
                </div>

                <form method="post" action="{{ $pkgSlug ? route('checkout.process', $pkgSlug) : '#' }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $ordId }}" />
                    <input type="hidden" name="payment_method" value="transfer_bank" />

                    <div class="form-group">
                        <label class="form-label">Nama Pemilik Rekening</label>
                        <input type="text" name="cardholder_name" class="form-input" value="{{ old('cardholder_name', auth()->user()->name ?? '') }}" placeholder="Contoh: Budi Santoso" required>
                        @error('cardholder_name') <div class="input-error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Rekening Pengirim</label>
                        <input type="text" name="card_number" class="form-input" value="{{ old('card_number') }}" placeholder="Contoh: 08123456789" required>
                        @error('card_number') <div class="input-error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bukti Transfer</label>
                        
                        <div class="upload-wrapper">
                            <input type="file" id="payment-proof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                            
                            <label for="payment-proof" class="upload-zone" id="upload-zone">
                                <div class="upload-icon-circle">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div>
                                    <p class="upload-text-main">Klik untuk unggah bukti pembayaran</p>
                                    <p class="upload-text-sub">Format JPG, PNG, atau PDF (Maks. 2MB)</p>
                                </div>
                            </label>

                            <div class="file-preview-card" id="file-preview">
                                <div class="file-info">
                                    <div class="file-icon-preview">📄</div>
                                    <div class="file-details">
                                        <span class="file-name" id="file-name-text">nama-file.jpg</span>
                                        <span class="file-status">Siap diunggah</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-remove-file" id="btn-remove-file" title="Batalkan upload">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>
                        </div>

                        @error('payment_proof') <div class="input-error-msg">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn-submit">Konfirmasi Pembayaran</button>
                </form>
            </main>

            <aside class="card summary-card">
                <h2 class="summary-title">Ringkasan Pesanan</h2>
                
                <div class="pkg-detail">
                    <div>
                        <div style="font-weight:700; color:var(--text-main);">{{ $pkgTitle }}</div>
                        <div style="font-size:0.9rem; color:var(--text-muted);">{{ $pkgStage }}</div>
                    </div>
                </div>

                <div class="summary-item">
                    <span>Harga Paket</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Biaya Layanan</span>
                    <span>Rp 0</span>
                </div>
                <div class="summary-item total">
                    <span>Total Bayar</span>
                    <span style="color: var(--primary);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">Ada kendala pembayaran?</p>
                    
                    {{-- TOMBOL WA OTOMATIS --}}
                    <a href="{{ $waLink }}" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 8px; color: var(--primary); font-weight: 600; font-size: 0.9rem; text-decoration: none; background: var(--primary-light); padding: 10px 16px; border-radius: 8px; width: 100%; justify-content: center;">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Hubungi Admin
                    </a>
                </div>
            </aside>

        </div>

        {{-- Scripts --}}
        <script>
            (function () {
                'use strict';

                // --- 1. Copy Logic ---
                function copyTextToClipboard(text) {
                    if (navigator.clipboard && window.isSecureContext) {
                        return navigator.clipboard.writeText(text);
                    }
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-9999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    return new Promise((res, rej) => {
                        document.execCommand('copy') ? res() : rej();
                        textArea.remove();
                    });
                }

                document.querySelectorAll('.btn-copy').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const target = document.querySelector(btn.dataset.target);
                        if(target) {
                            copyTextToClipboard(target.textContent.replace(/\s/g, '')).then(() => {
                                btn.classList.add('copied');
                                setTimeout(() => btn.classList.remove('copied'), 2000);
                            });
                        }
                    });
                });

                // --- 2. File Upload Preview Logic (REVISI) ---
                const fileInput = document.getElementById('payment-proof');
                const uploadZone = document.getElementById('upload-zone');
                const filePreview = document.getElementById('file-preview');
                const fileNameText = document.getElementById('file-name-text');
                const removeBtn = document.getElementById('btn-remove-file');

                if(fileInput) {
                    fileInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if(file) {
                            // Hide Dropzone, Show Preview Card
                            uploadZone.style.display = 'none';
                            filePreview.style.display = 'flex';
                            fileNameText.textContent = file.name;
                        }
                    });

                    removeBtn.addEventListener('click', function() {
                        // Reset input
                        fileInput.value = '';
                        // Show Dropzone, Hide Preview Card
                        filePreview.style.display = 'none';
                        uploadZone.style.display = 'flex';
                    });
                }

                // --- 3. Timer Logic ---
                const timerBox = document.querySelector('[data-countdown]');
                if (timerBox) {
                    let remaining = parseInt(timerBox.dataset.remaining, 10);
                    const display = timerBox.querySelector('[data-countdown-display]');
                    const expireUrl = timerBox.dataset.expireEndpoint;
                    const form = document.querySelector('form');
                    
                    const interval = setInterval(() => {
                        if (remaining <= 0) {
                            clearInterval(interval);
                            timerBox.dataset.status = 'expired';
                            display.textContent = "00:00";
                            if(form) {
                                form.querySelectorAll('input, button').forEach(el => {
                                    // Don't disable hidden inputs
                                    if(el.type !== 'hidden') {
                                        el.disabled = true;
                                        el.style.opacity = '0.6';
                                    }
                                });
                            }
                            if(expireUrl) fetch(expireUrl, { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content} });
                            return;
                        }
                        remaining--;
                        const m = Math.floor(remaining / 60).toString().padStart(2, '0');
                        const s = (remaining % 60).toString().padStart(2, '0');
                        display.textContent = `${m}:${s}`;
                    }, 1000);
                }
            })();
        </script>
    </body>
</html>