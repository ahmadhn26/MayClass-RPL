<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengaturan Akun - MayClass Admin</title>
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
            height: 40px;
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

        @media (max-width: 768px) {
            .btn-nav-back {
                padding: 6px 12px;
                font-size: 0.75rem;
                line-height: 1.2;
                text-align: center;
                max-width: 100px;
                white-space: normal; /* Allow wrapping */
            }
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

        /* --- Sidebar & Cards --- */
        .profile-card {
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

        .status-indicator {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 20px;
            height: 20px;
            background: #22c55e;
            border: 3px solid var(--surface);
            border-radius: 50%;
        }

        .admin-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 6px;
        }

        .admin-role {
            font-size: 0.85rem;
            color: var(--primary);
            background: var(--primary-light);
            padding: 6px 14px;
            border-radius: 99px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            text-align: left;
            padding-top: 24px;
            border-top: 1px solid var(--border);
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

        /* --- Content Area --- */
        .settings-container {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .form-card {
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

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 6px;
        }

        .section-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* Forms */
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
            display: inline-block;
            transition: all 0.2s;
        }

        .upload-btn:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-input {
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid var(--border);
            font-size: 0.95rem;
            color: var(--text-main);
            width: 100%;
            background: #fff;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.1);
        }

        .error-msg {
            font-size: 0.8rem;
            color: #ef4444;
            margin-top: 4px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 99px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .alert-box {
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #065f46;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
            }

            .profile-card {
                position: relative;
                top: 0;
                margin-bottom: 20px;
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
                <a href="{{ route('admin.dashboard') }}" class="btn-nav-back">Kembali ke Dashboard</a>
            </div>
        </nav>
    </header>

    <main class="container">
        @php($currentAdmin = $account ?? $admin)
        @php($avatarPlaceholder = asset('images/avatar-placeholder.svg'))
        @php($avatarSource = $avatarUrl ?? \App\Support\AvatarResolver::resolve([$currentAdmin?->avatar_path]) ?? $avatarPlaceholder)

        {{-- SIDEBAR: Profile Summary --}}
        <aside class="profile-card">
            <div class="avatar-wrapper">
                <img src="{{ $avatarSource }}" alt="Avatar" class="avatar-img" id="sidebar-avatar-preview">
                <div class="status-indicator" title="Online"></div>
            </div>

            <h2 class="admin-name">{{ $currentAdmin?->name ?? 'Administrator' }}</h2>
            <span class="admin-role">Super Admin</span>

            <div class="info-list">
                <div class="info-item">
                    <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ $currentAdmin?->email }}</span>
                </div>
                <div class="info-item">
                    <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                    <span>{{ $currentAdmin?->phone ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    <span>Akun Terverifikasi</span>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT: Forms --}}
        <div class="settings-container">

            {{-- Edit Profile Form --}}
            <div class="form-card">
                <div class="section-header">
                    <h3 class="section-title">Edit Profil</h3>
                    <p class="section-desc">Perbarui informasi pribadi admin utama.</p>
                </div>

                <form action="{{ route('admin.account.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="upload-area">
                        <img src="{{ $avatarSource }}" class="upload-preview" id="form-avatar-preview">
                        <div>
                            <label for="avatar" class="upload-btn">Ubah Foto</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" hidden>
                            <p class="error-msg" style="color: #64748b; margin-top: 6px;">JPG, GIF, atau PNG. Maksimal
                                5MB.</p>
                            @error('avatar') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-input"
                                value="{{ old('name', $currentAdmin?->name) }}" required>
                            @error('name') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input"
                                value="{{ old('email', $currentAdmin?->email) }}" required>
                            @error('email') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-input"
                                value="{{ old('phone', $currentAdmin?->phone) }}">
                            @error('phone') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div style="margin-top: 32px; text-align: right;">
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            {{-- Change Password Form --}}
            <div class="form-card">
                <div class="section-header">
                    <h3 class="section-title">Keamanan Akun</h3>
                    <p class="section-desc">Perbarui kata sandi untuk menjaga keamanan akses dashboard.</p>
                </div>

                @if (session('password_status'))
                    <div class="alert-box">{{ session('password_status') }}</div>
                @endif

                <form method="post" action="{{ route('admin.account.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="current_password" class="form-input" required>
                            @error('current_password', 'passwordUpdate') <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-input" required>
                            @error('password', 'passwordUpdate') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                    </div>

                    <div style="margin-top: 32px; text-align: right;">
                        <button type="submit" class="btn-primary" style="background-color: #0f172a;">Perbarui
                            Password</button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('avatar');
            const formPreview = document.getElementById('form-avatar-preview');
            const sidebarPreview = document.getElementById('sidebar-avatar-preview');

            if (input) {
                input.addEventListener('change', function () {
                    const file = input.files && input.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            if (formPreview) formPreview.src = e.target.result;
                            if (sidebarPreview) sidebarPreview.src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</body>

</html>