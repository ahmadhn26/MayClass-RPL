@extends('admin.layout')

@section('title', 'Pengaturan Akun - MayClass')

@push('styles')
    <style>
        .account-wrapper {
            display: grid;
            gap: 28px;
        }

        .account-header {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .account-header img {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--border);
        }

        .account-header h1 {
            margin: 0;
            font-size: 1.6rem;
        }

        .account-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }

        .password-card {
            margin-top: 12px;
            display: grid;
            gap: 16px;
        }

        .password-card h2 {
            margin: 0;
            font-size: 1.3rem;
        }

        .password-card p {
            margin: 0;
            color: var(--text-muted);
        }

        .password-form {
            display: grid;
            gap: 20px;
        }

        .password-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .password-input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: var(--surface-muted);
            font-family: inherit;
            font-size: 1rem;
        }

        .password-error {
            margin-top: 6px;
            font-size: 0.85rem;
            color: #dc2626;
        }

        .password-submit {
            width: fit-content;
            border: none;
            border-radius: 999px;
            padding: 12px 28px;
            background: #125e66;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(18, 94, 102, 0.2);
        }

        .password-alert {
            padding: 12px 16px;
            border-radius: 14px;
            background: rgba(18, 94, 102, 0.08);
            color: #125e66;
            font-weight: 500;
        }

        .avatar-field {
            display: grid;
            gap: 12px;
            justify-items: center;
            padding: 20px;
            border-radius: 16px;
            border: 1px dashed var(--border);
            background: var(--surface-muted);
        }

        .avatar-field__preview {
            width: 108px;
            height: 108px;
            border-radius: 50%;
            overflow: hidden;
            display: grid;
            place-items: center;
            background: #e2e8f0;
        }

        .avatar-field__preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-field__input {
            display: none;
        }

        .avatar-field__button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            font-weight: 600;
            cursor: pointer;
        }

        .avatar-field__hint {
            margin: 0;
            font-size: 0.85rem;
            color: var(--text-muted);
            text-align: center;
        }

        .avatar-field__error {
            margin: 0;
            font-size: 0.82rem;
            color: #dc2626;
            text-align: center;
        }

        form {
            display: grid;
            gap: 24px;
        }

        .form-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        label span {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input[type='text'],
        input[type='email'] {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: var(--surface-muted);
            font-family: inherit;
            font-size: 1rem;
        }

        .error-text {
            margin-top: 6px;
            font-size: 0.85rem;
            color: #dc2626;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
        }

        .form-actions button {
            padding: 12px 24px;
            border-radius: 999px;
            border: none;
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.25);
        }

        @media (max-width: 768px) {
            .account-header {
                flex-direction: column;
                text-align: center;
            }

            .form-actions {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    @php($currentAdmin = $account ?? $admin)
    @php($avatarPlaceholder = asset('images/avatar-placeholder.svg'))
    @php($avatarSource = $avatarUrl ?? \App\Support\AvatarResolver::resolve([$currentAdmin?->avatar_path]) ?? $avatarPlaceholder)
    <div class="account-wrapper">
        <div class="account-header">
            <img src="{{ $avatarSource }}" alt="Foto Admin" id="admin-account-avatar" />
            <div>
                <h1>Pengaturan Akun</h1>
                <p style="margin: 6px 0 0; color: rgba(17, 37, 54, 0.7);">
                    Kelola informasi profil admin utama untuk memastikan data terbaru tampil di seluruh panel.
                </p>
            </div>
        </div>

        <div class="account-card">
            <form action="{{ route('admin.account.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="avatar-field">
                    <div class="avatar-field__preview">
                        <img src="{{ $avatarSource }}" alt="Preview foto admin" id="admin-avatar-preview" />
                    </div>
                    <label class="avatar-field__button" for="avatar">Unggah Foto Baru</label>
                    <input type="file" class="avatar-field__input" id="avatar" name="avatar" accept="image/*" />
                    <p class="avatar-field__hint">Format JPG/PNG, maksimum 5 MB</p>
                    @error('avatar')
                        <p class="avatar-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-grid">
                    <label>
                        <span>Nama Lengkap</span>
                        <input type="text" name="name" value="{{ old('name', $currentAdmin?->name) }}" required />
                        @error('name')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </label>

                    <label>
                        <span>Email</span>
                        <input type="email" name="email" value="{{ old('email', $currentAdmin?->email) }}" required />
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </label>

                    <label>
                        <span>No. Telepon</span>
                        <input type="text" name="phone" value="{{ old('phone', $currentAdmin?->phone) }}" />
                        @error('phone')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
        <div class="account-card password-card">
            <h2>Keamanan Akun</h2>
            <p>Ubah password secara berkala untuk menjaga akses dashboard tetap aman.</p>
            @if (session('password_status'))
                <div class="password-alert">{{ session('password_status') }}</div>
            @endif
            <form class="password-form" method="post" action="{{ route('admin.account.password') }}">
                @csrf
                @method('PUT')
                <div class="password-grid">
                    <label>
                        <span>Password Lama</span>
                        <input class="password-input" type="password" name="current_password" autocomplete="current-password" required />
                        @error('current_password', 'passwordUpdate')
                            <div class="password-error">{{ $message }}</div>
                        @enderror
                    </label>
                    <label>
                        <span>Password Baru</span>
                        <input class="password-input" type="password" name="password" autocomplete="new-password" required />
                        @error('password', 'passwordUpdate')
                            <div class="password-error">{{ $message }}</div>
                        @enderror
                    </label>
                    <label>
                        <span>Konfirmasi Password Baru</span>
                        <input class="password-input" type="password" name="password_confirmation" autocomplete="new-password" required />
                    </label>
                </div>
                <button class="password-submit" type="submit">Perbarui Password</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('avatar');
            const preview = document.getElementById('admin-avatar-preview');

            if (!input || !preview) {
                return;
            }

            input.addEventListener('change', function () {
                const file = input.files && input.files[0];

                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.addEventListener('load', function (event) {
                    const result = event.target?.result;
                    if (typeof result === 'string') {
                        preview.src = result;
                        const headerPreview = document.getElementById('admin-account-avatar');
                        if (headerPreview) {
                            headerPreview.src = result;
                        }
                    }
                });
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
