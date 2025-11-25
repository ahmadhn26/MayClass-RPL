@extends('tutor.layout')

@section('title', 'Pengaturan Akun - MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;       /* Teal 700 */
            --primary-dark: #0f5132;  /* Teal 800 */
            --primary-light: rgba(15, 118, 110, 0.08);
            --surface: #ffffff;
            --background: #f8fafc;
            --border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --shadow-card: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --radius: 16px;
        }

        .account-layout {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 32px;
            align-items: start;
        }

        /* --- LEFT COLUMN: PROFILE CARD (CLEAN DESIGN) --- */
        .profile-card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
            position: sticky;
            top: 32px;
            padding: 48px 24px 32px; /* Padding atas lebih besar agar lega */
            text-align: center;
        }

        .profile-content {
            /* Container styling reset */
            padding: 0;
        }

        .avatar-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 24px; /* Jarak ke nama */
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            /* Shadow lembut menggantikan border tebal */
            box-shadow: 0 12px 24px -6px rgba(15, 118, 110, 0.15); 
            background: #fff;
        }

        .status-indicator {
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 18px;
            height: 18px;
            background: #22c55e;
            border: 3px solid var(--surface);
            border-radius: 50%;
        }

        .tutor-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 8px;
            letter-spacing: -0.01em;
        }

        .tutor-role {
            font-size: 0.85rem;
            color: var(--primary);
            background: var(--primary-light);
            padding: 6px 14px;
            border-radius: 99px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 24px;
            letter-spacing: 0.02em;
        }

        .tutor-bio {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 32px;
            padding: 0 8px;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            text-align: left;
            padding-top: 28px;
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

        /* --- RIGHT COLUMN: FORMS --- */
        .settings-container {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
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

        /* Custom Upload Area */
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

        /* Form Grid */
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
            transition: all 0.2s;
            color: var(--text-main);
            width: 100%;
            background: #fff;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.1);
        }

        .helper-text {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .error-msg {
            font-size: 0.8rem;
            color: #ef4444;
            margin-top: 4px;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 99px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
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
            .account-layout {
                grid-template-columns: 1fr;
            }
            .profile-card {
                position: relative;
                top: 0;
                margin-bottom: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="account-layout">
        
        {{-- SIDEBAR: Preview Profil (CLEAN DESIGN) --}}
        <aside class="profile-card">
            <div class="profile-content">
                @php($placeholderAvatar = asset('images/avatar-placeholder.svg'))
                @php($profileAvatar = $avatarUrl ?: $placeholderAvatar)
                
                <div class="avatar-wrapper">
                    <img src="{{ $profileAvatar }}" alt="Avatar" class="avatar-img" id="sidebar-avatar-preview">
                    <div class="status-indicator" title="Online"></div>
                </div>

                <h2 class="tutor-name">{{ $tutor?->name ?? 'Nama Tutor' }}</h2>
                <span class="tutor-role">{{ $tutorProfile?->specializations ?: 'Pengajar MayClass' }}</span>
                
                <p class="tutor-bio">
                    {{ $tutorProfile?->bio ?? 'Profil ini akan dilihat oleh calon siswa. Pastikan data Anda lengkap agar terlihat profesional.' }}
                </p>

                <div class="info-list">
                    <div class="info-item">
                        <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>{{ $tutorProfile?->experience_years ?? 0 }} Tahun Pengalaman</span>
                    </div>
                    <div class="info-item">
                        <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                        <span>{{ $tutorProfile?->education ?: 'Pendidikan belum diatur' }}</span>
                    </div>
                    <div class="info-item">
                        <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>{{ $tutor?->email }}</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT: Forms --}}
        <div class="settings-container">
            
            {{-- Form Profil --}}
            <div class="form-card">
                <div class="section-header">
                    <h3 class="section-title">Edit Profil</h3>
                    <p class="section-desc">Perbarui informasi pribadi dan detail profesional Anda.</p>
                </div>

                <form method="POST" action="{{ route('tutor.account.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Upload Area --}}
                    <div class="upload-area">
                        <img src="{{ $profileAvatar }}" class="upload-preview" id="form-avatar-preview">
                        <div>
                            <label for="avatar" class="upload-btn">Ubah Foto</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" hidden>
                            <p class="helper-text" style="margin-top: 6px;">JPG, GIF, atau PNG. Maksimal 2MB.</p>
                            @error('avatar') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-grid">
                        {{-- Kolom Kiri --}}
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $tutor?->name) }}" required>
                            @error('name') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $tutor?->email) }}" required>
                            @error('email') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor Telepon / WA</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', $tutor?->phone) }}">
                            @error('phone') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mata Pelajaran (Spesialisasi)</label>
                            <input type="text" name="specializations" class="form-input" value="{{ old('specializations', $tutorProfile?->specializations) }}" placeholder="Contoh: Fisika, Matematika Dasar">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pengalaman (Tahun)</label>
                            <input type="number" name="experience_years" class="form-input" value="{{ old('experience_years', $tutorProfile?->experience_years) }}" min="0">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="education" class="form-input" value="{{ old('education', $tutorProfile?->education) }}" placeholder="Contoh: S1 Pendidikan Matematika">
                        </div>
                    </div>

                    <div style="margin-top: 32px; text-align: right;">
                        <button type="submit" class="btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form Password --}}
            <div class="form-card">
                <div class="section-header">
                    <h3 class="section-title">Keamanan Akun</h3>
                    <p class="section-desc">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan.</p>
                </div>

                @if (session('password_status'))
                    <div class="alert-box">
                        {{ session('password_status') }}
                    </div>
                @endif

                <form method="post" action="{{ route('tutor.account.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="current_password" class="form-input" required>
                            @error('current_password', 'passwordUpdate') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-input" required>
                            @error('password', 'passwordUpdate') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                    </div>

                    <div style="margin-top: 32px; text-align: right;">
                        <button type="submit" class="btn-primary" style="background-color: #0f172a;">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
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
@endpush