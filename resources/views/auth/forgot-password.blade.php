<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>MayClass - Bantuan Lupa Password</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        />
        <style>
            :root {
                --primary: #0f766e;
                --primary-hover: #115e59;
                --primary-light: #ccfbf1;
                --text-main: #0f172a;
                --text-muted: #64748b;
                --bg-body: #f8fafc;
                --surface: #ffffff;
                --border: #e2e8f0;
                --radius: 16px;
                --shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
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

            a { text-decoration: none; color: inherit; transition: all 0.2s; }

            .container {
                width: 100%;
                max-width: 520px;
                display: flex;
                flex-direction: column;
                gap: 24px;
            }

            /* Header Nav */
            .nav-header {
                display: flex;
                align-items: center;
                margin-bottom: 12px;
            }

            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--text-muted);
                padding: 8px 16px;
                border-radius: 99px;
                background: white;
                border: 1px solid var(--border);
                transition: all 0.2s;
            }
            .back-link:hover {
                border-color: var(--text-muted);
                color: var(--text-main);
            }

            /* Main Card */
            .card {
                background: var(--surface);
                border-radius: var(--radius);
                padding: 40px;
                box-shadow: var(--shadow);
                border: 1px solid var(--border);
                text-align: center;
            }

            .icon-wrapper {
                width: 64px;
                height: 64px;
                background: var(--primary-light);
                color: var(--primary);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 24px;
                font-size: 28px;
            }

            .card h1 {
                font-size: 1.5rem;
                font-weight: 700;
                margin: 0 0 12px;
                color: var(--text-main);
            }

            .card p {
                font-size: 0.95rem;
                color: var(--text-muted);
                line-height: 1.6;
                margin: 0 0 32px;
            }

            /* Steps List */
            .steps-list {
                text-align: left;
                background: var(--bg-body);
                border-radius: 12px;
                padding: 20px;
                border: 1px solid var(--border);
                margin-bottom: 32px;
            }

            .step-item {
                display: flex;
                gap: 12px;
                margin-bottom: 16px;
            }
            .step-item:last-child { margin-bottom: 0; }

            .step-num {
                width: 24px;
                height: 24px;
                background: var(--primary);
                color: white;
                border-radius: 50%;
                font-size: 0.8rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                margin-top: 2px;
            }

            .step-content strong {
                display: block;
                font-size: 0.9rem;
                color: var(--text-main);
                margin-bottom: 2px;
            }
            .step-content span {
                font-size: 0.85rem;
                color: var(--text-muted);
                line-height: 1.4;
            }

            /* Contact Button */
            .whatsapp-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                background: #25D366;
                color: white;
                font-weight: 600;
                padding: 14px;
                border-radius: 12px;
                font-size: 1rem;
                transition: background 0.2s, transform 0.1s;
                box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
            }

            .whatsapp-btn:hover {
                background: #1ebc57;
                transform: translateY(-2px);
            }

            /* Footer Info */
            .footer-info {
                margin-top: 24px;
                font-size: 0.85rem;
                color: var(--text-muted);
                text-align: center;
                padding-top: 20px;
                border-top: 1px solid var(--border);
            }

            .admin-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #f1f5f9;
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 0.8rem;
                font-weight: 600;
                color: var(--text-main);
                margin-bottom: 8px;
            }

            @media (max-width: 480px) {
                .card { padding: 24px; }
            }
        </style>
    </head>
    <body>

        <div class="container">
            <div class="nav-header">
                <a class="back-link" href="{{ route('login') }}">
                    Kembali ke Login
                </a>
            </div>

            <main class="card">
                <div class="icon-wrapper">
                    🛡️
                </div>

                <h1>Lupa Password?</h1>
                <p>
                    Demi keamanan data siswa, reset password hanya dapat dilakukan melalui verifikasi manual oleh <strong>Admin MayClass</strong>.
                </p>

                <div class="steps-list">
                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-content">
                            <strong>Kirim Pesan</strong>
                            <span>Klik tombol di bawah untuk chat admin.</span>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">2</div>
                        <div class="step-content">
                            <strong>Verifikasi Data</strong>
                            <span>Sebutkan Nama & Kelas untuk pengecekan.</span>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">3</div>
                        <div class="step-content">
                            <strong>Terima Akses</strong>
                            <span>Admin akan memberikan password baru.</span>
                        </div>
                    </div>
                </div>

                @if ($whatsappLink)
                    <a class="whatsapp-btn" href="{{ $whatsappLink }}" target="_blank" rel="noopener">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                @else
                    <div class="steps-list" style="text-align: center;">
                        Admin belum tersedia saat ini.
                    </div>
                @endif

                <div class="footer-info">
                    <div class="admin-badge">{{ $contactName }}</div>
                    <div>{{ $contactNumber ?? '' }}</div>
                    <div style="margin-top: 4px;">Jam Operasional: {{ $availability }}</div>
                </div>
            </main>
        </div>

    </body>
</html>