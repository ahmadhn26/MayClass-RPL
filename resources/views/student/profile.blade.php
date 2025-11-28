<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Profil - MayClass</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            --primary: #0f766e;
            --primary-hover: #115e59;
            --primary-light: rgba(15, 118, 110, 0.08);
            --bg-body: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success: #10b981;
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        img {
            display: block;
            max-width: 100%;
        }

        /* --- Navbar --- */
        header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand img {
            height: 110px;
            width: auto;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-nav-back {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            background: var(--primary);
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-nav-back:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(15, 118, 110, 0.2);
        }

        .btn-logout {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            background: var(--danger);
            border: 1px solid var(--danger);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #dc2626;
            border-color: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
        }

        /* --- Main Layout --- */
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 32px;
            align-items: start;
        }

        /* --- Left Column: Avatar Card --- */
        .profile-sidebar {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 40px 24px 32px;
            text-align: center;
            box-shadow: var(--shadow-card);
            position: sticky;
            top: 100px;
        }

        .avatar-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--surface);
            box-shadow: 0 12px 24px -6px rgba(15, 118, 110, 0.15);
            background: #fff;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid var(--surface);
        }

        .avatar-placeholder svg {
            width: 48px;
            height: 48px;
        }

        .profile-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 6px;
        }

        .profile-id {
            font-family: monospace;
            font-size: 0.85rem;
            color: var(--primary);
            background: var(--primary-light);
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
            font-weight: 600;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            text-align: left;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            margin-top: 24px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.9rem;
            color: var(--text-main);
            font-weight: 500;
        }

        .info-icon {
            color: #94a3b8;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* --- Right Column: Forms --- */
        .content-area {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 32px;
            box-shadow: var(--shadow-card);
        }

        .section-header {
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 6px;
        }

        .card-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--text-main);
            background: var(--surface);
            transition: all 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        input[readonly] {
            background: var(--bg-body);
            color: var(--text-muted);
            cursor: not-allowed;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Upload Area */
        .upload-area {
            display: flex;
            align-items: center;
            gap: 24px;
            padding: 24px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            margin-bottom: 32px;
        }

        .upload-preview {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .upload-btn {
            background: #fff;
            border: 1px solid #cbd5e1;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-main);
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
        }

        .upload-btn:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        /* Buttons */
        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 99px;
            font-weight: 600;
            font-size: 0.95rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: white;
            border-color: var(--border);
            color: var(--text-muted);
        }

        .btn-secondary:hover {
            border-color: var(--text-muted);
            color: var(--text-main);
        }

        /* Alerts */
        .alert {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .alert-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .input-error-msg {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 4px;
        }

        @media (max-width: 900px) {
            .container {
                grid-template-columns: 1fr;
            }

            .profile-sidebar {
                position: static;
                display: flex;
                align-items: center;
                gap: 24px;
                text-align: left;
            }

            .avatar-wrapper {
                margin: 0;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .profile-sidebar {
                flex-direction: column;
                text-align: center;
            }

            .avatar-wrapper {
                margin: 0 auto 16px;
            }

            .nav-actions {
                display: none;
            }
        }
    </style>
</head>

<body>

    <header>
        <nav>
            <a href="/" class="brand">
                <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" />
            </a>
            <div class="nav-actions">
                @if($hasActivePackage ?? false)
                    <a href="{{ route('student.dashboard') }}" class="btn-nav-back">Kembali ke Dashboard</a>
                @else
                    <a href="/" class="btn-nav-back">Kembali ke Beranda</a>
                @endif
                <form method="post" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar</button>
                </form>
            </div>
        </nav>
    </header>

    <main class="container">

        @php($avatarUrl = $avatarUrl ?? null)

        <aside class="profile-sidebar">
            <div class="avatar-wrapper">
                <div data-avatar-preview style="width: 100%; height: 100%;">
                    <img src="{{ $avatarUrl ?? '' }}" alt="Foto profil" class="avatar-img" data-avatar-image
                        data-original="{{ $avatarUrl ?? '' }}" @if (!$avatarUrl) style="display: none;" @endif />
                    <div class="avatar-placeholder" data-avatar-placeholder @if($avatarUrl) style="display: none;"
                    @endif>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                </div>
            </div>

            <h2 class="profile-name">{{ $profile['name'] }}</h2>
            <span class="profile-id">ID: {{ $profile['studentId'] }}</span>

            <div class="info-list">
                <div class="info-item">
                    <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ $profile['email'] }}</span>
                </div>
                <div class="info-item">
                    <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                    <span>{{ $profile['phone'] ?? '-' }}</span>
                </div>
            </div>
        </aside>

        <div class="content-area">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    Terjadi kesalahan. Silakan periksa input Anda.
                </div>
            @endif

            <form action="{{ route('student.profile.update') }}" method="post" enctype="multipart/form-data"
                class="card">
                @csrf
                <div class="section-header">
                    <h3 class="card-title">Informasi Pribadi</h3>
                    <p class="card-desc">Perbarui data diri Anda untuk keperluan administrasi.</p>
                </div>

                <div class="upload-area">
                    <div
                        style="width: 72px; height: 72px; border-radius: 50%; overflow: hidden; border: 2px solid #e2e8f0;">
                        <img src="{{ $avatarUrl ?? '' }}" alt="Preview" data-avatar-image
                            style="width: 100%; height: 100%; object-fit: cover; @if(!$avatarUrl) display: none; @endif" />
                        <div data-avatar-placeholder
                            style="width: 100%; height: 100%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; @if($avatarUrl) display: none; @endif">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label for="avatar" class="upload-btn">Ubah Foto</label>
                        <input id="avatar" name="avatar" type="file" accept="image/*" style="display: none;" />
                        <p class="input-error-msg" style="color: #64748b; margin-top: 6px;">JPG/PNG, Maks. 5MB</p>
                        @error('avatar') <p class="input-error-msg">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $profile['name']) }}" required />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $profile['email']) }}"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="phone">No. Telepon / WA</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $profile['phone']) }}" />
                    </div>
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select id="gender" name="gender">
                            <option value="">Pilih jenis kelamin</option>
                            @foreach ($genderOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('gender', $profile['gender']) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="parent">Nama Orang Tua</label>
                        <input id="parent" name="parent_name" type="text"
                            value="{{ old('parent_name', $profile['parentName']) }}" />
                    </div>
                    <div class="form-group">
                        <label for="parent-phone">No. WhatsApp Orang Tua / Wali <span
                                style="color: var(--danger);">*</span></label>
                        <input id="parent-phone" name="parent_phone" type="text" placeholder="Contoh: 081234567890"
                            value="{{ old('parent_phone', $profile['parentPhone']) }}" required />
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 4px;">
                            Nomor ini akan digunakan untuk konseling dan komunikasi dengan orang tua
                        </p>
                        @error('parent_phone') <p class="input-error-msg">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="student-code">ID Siswa</label>
                        <input id="student-code" type="text" value="{{ $profile['studentId'] }}" readonly />
                    </div>
                    <div class="form-group full">
                        <label for="address">Alamat Lengkap</label>
                        <textarea id="address" name="address">{{ old('address', $profile['address']) }}</textarea>
                    </div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-secondary" type="reset">Reset</button>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>

            <form action="{{ route('student.profile.password') }}" method="post" class="card">
                @csrf
                @method('PUT')
                <div class="section-header">
                    <h3 class="card-title">Keamanan Akun</h3>
                    <p class="card-desc">Perbarui kata sandi untuk menjaga keamanan akun Anda.</p>
                </div>

                @if (session('password_status'))
                    <div class="alert alert-success" style="margin-bottom: 20px;">{{ session('password_status') }}</div>
                @endif

                <div class="form-grid">
                    <div class="form-group full">
                        <label>Password Lama</label>
                        <input type="password" name="current_password" required />
                        @error('current_password', 'passwordUpdate') <div class="input-error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" required />
                        @error('password', 'passwordUpdate') <div class="input-error-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required />
                    </div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-primary" type="submit" style="background-color: #0f172a;">Perbarui
                        Password</button>
                </div>
            </form>

        </div>
    </main>

    {{-- Script Preview Avatar --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('avatar');
            const imagePreviews = document.querySelectorAll('[data-avatar-image]');
            const placeholders = document.querySelectorAll('[data-avatar-placeholder]');

            if (!input) return;

            input.addEventListener('change', function () {
                const file = input.files && input.files[0];

                if (file) {
                    const url = URL.createObjectURL(file);

                    // Update all previews
                    imagePreviews.forEach(img => {
                        img.style.display = 'block';
                        img.src = url;
                    });

                    // Hide all placeholders
                    placeholders.forEach(ph => {
                        ph.style.display = 'none';
                    });
                }
            });
        });
    </script>
</body>

</html>