<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Edit Profil - MayClass</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />
        <style>
            :root {
                /* Color Palette */
                --primary: #0f766e;
                --primary-hover: #115e59;
                --primary-light: #ccfbf1;
                --bg-body: #f8fafc;
                --surface: #ffffff;
                --text-main: #0f172a;
                --text-muted: #64748b;
                --border: #e2e8f0;
                --danger: #ef4444;
                --success: #10b981;
                
                /* Spacing & Radius */
                --radius: 16px;
                --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
                --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-body);
                color: var(--text-main);
                line-height: 1.6;
                -webkit-font-smoothing: antialiased;
            }

            a { text-decoration: none; color: inherit; transition: all 0.2s; }
            img { display: block; max-width: 100%; }

            /* --- Navbar --- */
            header {
                background: var(--surface);
                border-bottom: 1px solid var(--border);
                position: sticky;
                top: 0;
                z-index: 50;
            }

            nav {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 24px;
                height: 72px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .brand {
                display: flex;
                align-items: center;
                gap: 10px;
                font-weight: 800;
                font-size: 1.25rem;
                color: var(--primary);
            }
            .brand img { height: 110px; width: auto; }

            .nav-actions { display: flex; gap: 12px; align-items: center; }

            .btn-nav-back {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--text-muted);
                padding: 8px 16px;
                border-radius: 8px;
            }
            .btn-nav-back:hover { background: var(--bg-body); color: var(--text-main); }

            .btn-logout {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--danger);
                background: transparent;
                border: 1px solid transparent;
                padding: 8px 16px;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s;
            }
            .btn-logout:hover { background: #fef2f2; border-color: #fecaca; }

            /* --- Main Layout --- */
            .container {
                max-width: 1100px;
                margin: 40px auto;
                padding: 0 24px;
                display: grid;
                grid-template-columns: 300px 1fr; /* Sidebar Fixed - Content Flexible */
                gap: 32px;
                align-items: start;
            }

            /* --- Left Column: Avatar Card --- */
            .profile-sidebar {
                background: var(--surface);
                border-radius: var(--radius);
                border: 1px solid var(--border);
                padding: 32px 24px;
                text-align: center;
                box-shadow: var(--shadow-sm);
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
                border: 4px solid var(--bg-body);
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
                border: 4px solid var(--bg-body);
            }

            .avatar-placeholder svg {
                width: 48px;
                height: 48px;
            }

            .avatar-label {
                display: inline-block;
                padding: 8px 16px;
                background: var(--bg-body);
                color: var(--text-main);
                border: 1px solid var(--border);
                border-radius: 99px;
                font-size: 0.85rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            .avatar-label:hover { border-color: var(--primary); color: var(--primary); }

            .avatar-hint { font-size: 0.75rem; color: var(--text-muted); margin-top: 8px; }
            .avatar-error { color: var(--danger); font-size: 0.8rem; margin-top: 4px; }

            .profile-name {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--text-main);
                margin: 16px 0 4px;
            }
            .profile-id {
                font-family: monospace;
                font-size: 0.9rem;
                color: var(--text-muted);
                background: var(--bg-body);
                padding: 4px 8px;
                border-radius: 6px;
                display: inline-block;
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
                box-shadow: var(--shadow-sm);
            }

            .card-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--text-main);
                margin: 0 0 24px;
                padding-bottom: 16px;
                border-bottom: 1px solid var(--border);
            }

            /* Form Grid */
            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .form-group { display: flex; flex-direction: column; gap: 8px; }
            .form-group.full { grid-column: 1 / -1; }

            label {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--text-main);
            }

            input, select, textarea {
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

            input:focus, select:focus, textarea:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px var(--primary-light);
            }

            input[readonly] {
                background: var(--bg-body);
                color: var(--text-muted);
                cursor: not-allowed;
            }

            textarea { min-height: 100px; resize: vertical; }

            /* Buttons */
            .btn-group {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                margin-top: 24px;
            }

            .btn {
                padding: 12px 24px;
                border-radius: 8px;
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
            .btn-primary:hover { background: var(--primary-hover); }

            .btn-secondary {
                background: white;
                border-color: var(--border);
                color: var(--text-muted);
            }
            .btn-secondary:hover { border-color: var(--text-muted); color: var(--text-main); }

            /* Alerts */
            .alert {
                padding: 14px;
                border-radius: 10px;
                margin-bottom: 24px;
                font-size: 0.9rem;
                text-align: center;
            }
            .alert-success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
            .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

            .input-error-msg { color: var(--danger); font-size: 0.85rem; margin-top: 4px; }

            @media (max-width: 900px) {
                .container { grid-template-columns: 1fr; }
                .profile-sidebar { position: static; display: flex; align-items: center; gap: 24px; text-align: left; }
                .avatar-wrapper { margin: 0; }
                .form-grid { grid-template-columns: 1fr; }
            }

            @media (max-width: 600px) {
                .profile-sidebar { flex-direction: column; text-align: center; }
                .avatar-wrapper { margin: 0 auto 16px; }
                .nav-actions { display: none; } /* Sembunyikan aksi di mobile jika perlu atau buat hamburger */
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
                    <a href="{{ route('student.dashboard') }}" class="btn-nav-back">Kembali ke Dashboard</a>
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
                        <img
                            src="{{ $avatarUrl ?? '' }}"
                            alt="Foto profil"
                            class="avatar-img"
                            data-avatar-image
                            data-original="{{ $avatarUrl ?? '' }}"
                            @if (! $avatarUrl) style="display: none;" @endif
                        />
                        <div class="avatar-placeholder" data-avatar-placeholder @if($avatarUrl) style="display: none;" @endif>
                            {{-- SVG USER ICON --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="profile-info">
                    <h2 class="profile-name">{{ $profile['name'] }}</h2>
                    <span class="profile-id">ID: {{ $profile['studentId'] }}</span>
                    
                    <div style="margin-top: 20px;">
                        <label for="avatar" class="avatar-label">Ubah Foto</label>
                        <p class="avatar-hint">JPG/PNG, Maks. 5MB</p>
                        @error('avatar')
                            <p class="avatar-error">{{ $message }}</p>
                        @enderror
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

                <form action="{{ route('student.profile.update') }}" method="post" enctype="multipart/form-data" class="card">
                    @csrf
                    <h3 class="card-title">Informasi Pribadi</h3>
                    
                    <input id="avatar" name="avatar" type="file" accept="image/*" style="display: none;" />

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $profile['name']) }}" required />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $profile['email']) }}" required />
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
                            <input id="parent" name="parent_name" type="text" value="{{ old('parent_name', $profile['parentName']) }}" />
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
                    <h3 class="card-title">Keamanan Akun</h3>

                    @if (session('password_status'))
                        <div class="alert alert-success" style="margin-bottom: 20px;">{{ session('password_status') }}</div>
                    @endif

                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Password Lama</label>
                            <input type="password" name="current_password" required />
                            @error('current_password', 'passwordUpdate') <div class="input-error-msg">{{ $message }}</div> @enderror
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
                        <button class="btn btn-primary" type="submit">Perbarui Password</button>
                    </div>
                </form>

            </div>
        </main>

        {{-- Script Preview Avatar --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const input = document.getElementById('avatar');
                const imagePreview = document.querySelector('[data-avatar-image]');
                const placeholder = document.querySelector('[data-avatar-placeholder]');
                const filename = document.querySelector('.avatar-upload__filename');

                if (!input) return;

                input.addEventListener('change', function () {
                    const file = input.files && input.files[0];

                    if (file) {
                        // Tampilkan Preview
                        if (imagePreview) {
                            imagePreview.style.display = 'block';
                            imagePreview.src = URL.createObjectURL(file);
                        }
                        // Sembunyikan Placeholder
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    } else {
                        // Reset jika batal pilih file
                        if (imagePreview) {
                            const original = imagePreview.getAttribute('data-original');
                            if (original) {
                                imagePreview.src = original;
                            } else {
                                imagePreview.style.display = 'none';
                                if (placeholder) placeholder.style.display = 'flex';
                            }
                        }
                    }
                });
            });
        </script>
    </body>
</html>