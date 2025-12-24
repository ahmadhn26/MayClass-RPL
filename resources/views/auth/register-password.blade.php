<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MayClass - Konfirmasi Password</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    {{-- Script reCAPTCHA (Wajib Ada) --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        :root {
            /* --- Color Palette (Sama dengan Login Page) --- */
            --primary-600: #0f766e;
            --primary-700: #0d655d;
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-400: #94a3b8;
            --neutral-500: #64748b;
            --neutral-900: #0f172a;
            --danger: #ef4444;
            --success: #10b981;
            --radius-md: 12px;
            --radius-lg: 24px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--neutral-900);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            padding: 20px;

            background-color: #0f172a;
            background-image:
                linear-gradient(rgba(15, 23, 42, 0.5), rgba(15, 23, 42, 0.7)),
                url("{{ asset('images/bg_kelas.jpg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        /* --- GLASSMORPHISM CARD --- */
        .auth-container {
            width: 100%;
            max-width: 480px;

            /* Efek Kaca / Transparan */
            background: rgba(255, 255, 255, 0.56);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);

            border-radius: var(--radius-lg);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
            padding: 40px;
            position: relative;
        }

        /* Tombol Kembali di Pojok Kiri Atas */
        .back-button {
            position: absolute;
            top: 24px;
            left: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--neutral-500);
            padding: 8px 16px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
        }

        .back-button:hover {
            background: #fff;
            color: var(--neutral-900);
            transform: translateY(-1px);
        }

        /* Header Text */
        .header-text {
            text-align: center;
            margin-top: 32px;
            margin-bottom: 24px;
        }

        .header-text h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--neutral-900);
            margin: 0 0 8px 0;
            letter-spacing: -0.02em;
        }

        .header-text p {
            color: var(--neutral-500);
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.5;
        }

        /* --- Summary Box (Data Diri) --- */
        .summary-box {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: var(--radius-md);
            padding: 20px;
            margin-bottom: 24px;
        }

        .summary-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--neutral-500);
            letter-spacing: 0.05em;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--neutral-200);
            padding-bottom: 8px;
        }

        .summary-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }

        .summary-label {
            color: var(--neutral-500);
        }

        .summary-value {
            font-weight: 600;
            color: var(--neutral-900);
            text-align: right;
        }

        /* --- Form Elements --- */
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--neutral-900);
        }

        .input-field {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--neutral-200);
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.6);
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--neutral-900);
            transition: all 0.2s;
        }

        .input-field:focus {
            outline: none;
            background: #fff;
            border-color: var(--primary-600);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.15);
        }

        .btn-primary {
            background: var(--primary-600);
            color: white;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-700);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        /* Alerts & Helpers */
        .error-msg {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 2px;
        }

        .error-alert {
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .error-alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .form-helper {
            font-size: 0.85rem;
            color: var(--neutral-500);
            text-align: center;
            margin-top: 16px;
            margin-bottom: 4px;
        }

        /* Link di Bawah (Edit Data) */
        .auth-footer {
            margin-top: 12px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--neutral-500);
        }

        .action-link {
            color: var(--primary-600);
            font-weight: 600;
            text-decoration: underline;
        }

        /* Recaptcha Wrapper */
        .recaptcha-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .auth-container {
                padding: 20px 16px;
                border-radius: 16px;
            }

            .back-button {
                top: 12px;
                left: 12px;
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            .header-text {
                margin-top: 24px;
                margin-bottom: 16px;
            }

            .header-text h2 {
                font-size: 1.3rem;
            }

            .header-text p {
                font-size: 0.85rem;
            }

            .summary-box {
                padding: 14px;
                margin-bottom: 16px;
            }

            .summary-title {
                font-size: 0.75rem;
                margin-bottom: 10px;
                padding-bottom: 6px;
            }

            .summary-grid {
                gap: 8px;
            }

            .summary-item {
                font-size: 0.8rem;
            }

            .input-group {
                gap: 4px;
                margin-bottom: 12px;
            }

            .label {
                font-size: 0.8rem;
            }

            .input-field {
                padding: 10px 12px;
                font-size: 0.85rem;
                border-radius: 10px;
            }

            .btn-primary {
                padding: 10px;
                font-size: 0.9rem;
                margin-top: 4px;
            }

            .form-helper {
                font-size: 0.8rem;
                margin-top: 12px;
            }

            .error-alert {
                padding: 10px;
                font-size: 0.8rem;
                margin-bottom: 14px;
            }

            .recaptcha-wrapper {
                transform: scale(0.85);
                transform-origin: center;
            }
        }
    </style>
</head>

<body>

    {{-- Logic Gender (Tetap Dipertahankan) --}}
    @php($genderLabel = match ($profile['gender'] ?? null) {
        'female' => 'Perempuan',
        'male' => 'Laki-laki',
        'default' => 'Tidak disebutkan',
    })

    <div class="auth-container">
        {{-- Tombol Kembali --}}
        <a class="back-button" href="{{ route('register') }}">
            Kembali
        </a>

        {{-- Header --}}
        <div class="header-text">
            <h2>Konfirmasi Password</h2>
            <p>Tinjau data diri dan buat password untuk keamanan akun.</p>
        </div>

        {{-- Alert Error Global --}}
        @if ($errors->any())
            <div class="error-alert" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Summary Data Diri --}}
        <div class="summary-box">
            <div class="summary-title">Ringkasan Data</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Nama Lengkap</span>
                    <span class="summary-value">{{ $profile['name'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Username</span>
                    <span class="summary-value">{{ $profile['username'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Email</span>
                    <span class="summary-value">{{ $profile['email'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">No. Tlp / WA</span>
                    <span class="summary-value">{{ $profile['phone'] ?? '-' }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">WA Orang Tua</span>
                    <span class="summary-value">{{ $profile['parent_phone'] ?? '-' }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Jenis Kelamin</span>
                    <span class="summary-value">{{ $genderLabel }}</span>
                </div>
            </div>
        </div>

        {{-- Form Set Password --}}
        <form method="post" action="{{ route('register.perform') }}" novalidate>
            @csrf

            <div class="input-group">
                <label class="label" for="register-password">Password Baru</label>
                <input id="register-password" class="input-field" type="password" name="password"
                    autocomplete="new-password" placeholder="Minimal 8 karakter" required />
                @error('password') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="input-group">
                <label class="label" for="register-password-confirmation">Konfirmasi Password</label>
                <input id="register-password-confirmation" class="input-field" type="password"
                    name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password" required />
                @error('password_confirmation') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            {{-- reCAPTCHA --}}
            @if(config('services.recaptcha.key'))
                <div class="input-group" style="margin-top: 20px;">
                    <div class="recaptcha-wrapper">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                    </div>
                    @error('g-recaptcha-response') <p class="error-msg" style="text-align:center">{{ $message }}</p>
                    @enderror
                </div>
            @else
                @if(app()->environment('local', 'development'))
                    <div class="input-group" style="margin-top: 12px;">
                        <p class="form-helper" style="color: #f59e0b; font-size: 0.85rem; text-align: center; margin: 0;">
                            ⚠️ Development Mode: reCAPTCHA tidak dikonfigurasi (validasi di-skip)
                        </p>
                    </div>
                @endif
            @endif

            <button class="btn-primary" type="submit">Simpan &amp; Daftar</button>

            <p class="form-helper">
                Data tidak sesuai?
                <a class="action-link" href="{{ route('register') }}">Perbarui data diri</a>
            </p>
        </form>
    </div>

</body>

</html>