<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pembayaran Berhasil - MayClass</title>
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
                --surface: #ffffff;
                --bg-body: #f8fafc;
                --text-main: #0f172a;
                --text-muted: #64748b;
                --border: #e2e8f0;
                --radius: 24px;
                --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-body);
                color: var(--text-main);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }

            /* --- Main Card --- */
            .success-card {
                background: var(--surface);
                border-radius: var(--radius);
                padding: 48px 40px;
                max-width: 600px;
                width: 100%;
                box-shadow: var(--shadow-lg);
                text-align: center;
                border: 1px solid var(--border);
                position: relative;
                overflow: hidden;
            }

            /* Dekorasi latar belakang halus */
            .success-card::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; height: 6px;
                background: linear-gradient(90deg, var(--primary) 0%, #2dd4bf 100%);
            }

            /* --- Icon Status --- */
            .status-icon-wrapper {
                width: 80px; height: 80px;
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 24px;
            }
            
            .status-success {
                background: #ecfdf5;
                color: #10b981;
            }
            
            .status-pending {
                background: #fffbeb;
                color: #f59e0b;
            }

            .status-icon-wrapper svg {
                width: 40px; height: 40px;
            }

            /* --- Text Content --- */
            h1 {
                margin: 0 0 12px;
                font-size: 1.75rem;
                font-weight: 800;
                letter-spacing: -0.025em;
                color: var(--text-main);
            }

            p.description {
                margin: 0 0 32px;
                color: var(--text-muted);
                font-size: 1rem;
                line-height: 1.6;
            }

            /* --- Order Summary Box --- */
            .summary-box {
                background: var(--bg-body);
                border: 1px solid var(--border);
                border-radius: 16px;
                padding: 24px;
                text-align: left;
                margin-bottom: 32px;
            }

            .summary-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 12px;
                font-size: 0.95rem;
                color: var(--text-muted);
            }
            
            .summary-item strong {
                color: var(--text-main);
                font-weight: 600;
            }

            .summary-divider {
                height: 1px;
                background: var(--border);
                margin: 16px 0;
            }

            .summary-total {
                display: flex;
                justify-content: space-between;
                font-weight: 700;
                font-size: 1.1rem;
                color: var(--primary);
            }

            /* --- Button --- */
            .btn-primary {
                display: inline-block;
                width: 100%;
                padding: 14px;
                background: var(--primary);
                color: white;
                font-weight: 700;
                text-decoration: none;
                border-radius: 12px;
                transition: background 0.2s, transform 0.1s;
                box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
            }
            .btn-primary:hover {
                background: var(--primary-hover);
                transform: translateY(-2px);
            }

            /* --- Modal Activation --- */
            .activation-modal {
                position: fixed; inset: 0;
                display: flex; align-items: center; justify-content: center;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(4px);
                z-index: 999; opacity: 0; pointer-events: none;
                transition: opacity 0.2s ease;
            }
            .activation-modal.is-visible { opacity: 1; pointer-events: auto; }

            .modal-content {
                background: white; padding: 40px;
                border-radius: 24px; text-align: center;
                max-width: 400px; width: 90%;
                box-shadow: var(--shadow-lg);
            }
            
            .modal-icon {
                width: 64px; height: 64px; background: #ecfdf5;
                color: var(--primary); border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 20px; font-size: 32px;
            }

            @media (max-width: 600px) {
                .success-card { padding: 32px 24px; }
                h1 { font-size: 1.5rem; }
            }
        </style>
    </head>
    <body>

        @php
            $status = $order->status ?? 'pending';
            $isPending = $status === 'pending';
            $pkgTitle = $package['detail_title'] ?? 'Paket Belajar';
            $pkgStage = $package['stage_label'] ?? $package['stage'] ?? 'Program';
            $orderIdDisplay = 'MC-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
            $totalFormatted = number_format((int) $order->total, 0, ',', '.');
        @endphp

        <main class="success-card">
            
            <div class="status-icon-wrapper {{ $isPending ? 'status-pending' : 'status-success' }}">
                @if ($isPending)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                @endif
            </div>

            <h1>{{ $isPending ? 'Menunggu Verifikasi' : 'Pembayaran Berhasil!' }}</h1>
            <p class="description">
                @if ($isPending)
                    Terima kasih! Bukti pembayaran Anda telah kami terima. Admin akan memverifikasi data Anda maksimal <strong>1x24 jam</strong>.
                @else
                    Selamat! Paket <strong>{{ $pkgTitle }}</strong> sudah aktif. Anda sekarang memiliki akses penuh ke semua materi.
                @endif
            </p>

            <div class="summary-box">
                <div class="summary-item">
                    <span>ID Pesanan</span>
                    <strong>{{ $orderIdDisplay }}</strong>
                </div>
                <div class="summary-item">
                    <span>Paket Belajar</span>
                    <strong>{{ $pkgTitle }}</strong>
                </div>
                <div class="summary-item">
                    <span>Jenjang</span>
                    <strong>{{ $pkgStage }}</strong>
                </div>
                
                <div class="summary-divider"></div>
                
                <div class="summary-total">
                    <span>Total Dibayar</span>
                    <span>Rp {{ $totalFormatted }}</span>
                </div>
            </div>

            <a href="{{ route('student.dashboard') }}" class="btn-primary">
                Kembali ke Dashboard
            </a>

        </main>

        <footer style="margin-top: 32px; color: var(--text-muted); font-size: 0.85rem;">
            © {{ now()->year }} MayClass. Tetap semangat belajar!
        </footer>

        {{-- Modal Aktivasi Otomatis (Jika ada trigger dari server) --}}
        @if (! empty($showActivationModal))
            <div class="activation-modal" data-activation-modal aria-live="assertive">
                <div class="modal-content">
                    <div class="modal-icon">🎉</div>
                    <h2 style="font-size:1.5rem; font-weight:700; margin:0 0 8px; color:var(--text-main);">Paket Aktif!</h2>
                    <p style="color:var(--text-muted); margin-bottom:24px;">Pembayaran Anda telah diverifikasi.</p>
                    <button type="button" class="btn-primary" data-start-learning="true">Mulai Belajar</button>
                </div>
            </div>
        @endif

        {{-- Script Polling Status (Hanya jika Pending) --}}
        @if ($isPending && ! empty($statusCheckUrl ?? null))
            <script>
                (function () {
                    const statusUrl = @json($statusCheckUrl);
                    const refreshUrl = new URL(@json(route('checkout.success', ['slug' => $package['slug'], 'order' => $order->id])), window.location.origin);
                    refreshUrl.searchParams.set('activated', '1');
                    const retryDelay = 5000; // Cek setiap 5 detik

                    const pollStatus = () => {
                        fetch(statusUrl, {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        })
                        .then(res => res.ok ? res.json() : Promise.reject())
                        .then(payload => {
                            if (payload?.status === 'paid' || payload?.should_redirect) {
                                window.location.href = refreshUrl.toString();
                            } else {
                                setTimeout(pollStatus, retryDelay);
                            }
                        })
                        .catch(() => setTimeout(pollStatus, retryDelay * 2));
                    };

                    pollStatus();
                })();
            </script>
        @endif

        {{-- Script Modal Aktivasi --}}
        @if (! empty($showActivationModal))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.querySelector('[data-activation-modal]');
                    const btn = document.querySelector('[data-start-learning]');
                    const dest = @json($activationRedirectUrl ?? route('student.dashboard'));

                    if (modal) {
                        setTimeout(() => modal.classList.add('is-visible'), 100);
                        btn?.addEventListener('click', () => window.location.href = dest);
                    }

                    // Bersihkan URL query param agar modal tidak muncul terus saat refresh
                    const url = new URL(window.location.href);
                    if (url.searchParams.has('activated')) {
                        url.searchParams.delete('activated');
                        window.history.replaceState({}, document.title, url.toString());
                    }
                });
            </script>
        @endif

    </body>
</html>