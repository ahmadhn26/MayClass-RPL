@extends('admin.layout')

@section('title', 'Tambah Mata Pelajaran - MayClass')

@push('styles')
    <style>
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 26px;
            padding: 28px 32px;
            border: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
            max-width: 680px;
        }

        .form-grid {
            display: grid;
            gap: 22px;
        }

        .form-grid .full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 0.92rem;
            margin-bottom: 8px;
        }

        input,
        textarea,
        select {
            width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            padding: 12px 14px;
            font-size: 0.95rem;
            font-family: inherit;
            background: rgba(248, 250, 252, 0.9);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        .form-actions {
            margin-top: 28px;
            display: flex;
            gap: 16px;
        }

        .submit-btn {
            padding: 12px 22px;
            border-radius: 16px;
            border: none;
            background: linear-gradient(135deg, rgba(31, 209, 161, 0.85), rgba(84, 101, 255, 0.85));
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .cancel-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            font-weight: 500;
            text-decoration: none;
        }

        .error-message {
            margin-top: 6px;
            color: #dc2626;
            font-size: 0.85rem;
        }

        .help-text {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 0.85rem;
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.subjects.index') }}" class="cancel-link" style="margin-bottom: 22px;">← Kembali ke daftar mata pelajaran</a>

    <form action="{{ route('admin.subjects.store') }}" method="POST" class="form-card">
        @csrf
        <h2 style="margin-top: 0; font-size: 1.6rem;">Tambah Mata Pelajaran</h2>
        <p style="color: var(--text-muted); margin-top: 6px;">Tambahkan mata pelajaran baru untuk jenjang pendidikan tertentu.</p>

        <div class="form-grid">
            <div>
                <label for="name">Nama Mata Pelajaran *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Matematika, Fisika, Bahasa Inggris" required />
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="level">Jenjang Pendidikan *</label>
                <select id="level" name="level" required>
                    <option value="" disabled {{ old('level') ? '' : 'selected' }}>Pilih jenjang</option>
                    @foreach ($levels as $value => $label)
                        <option value="{{ $value }}" {{ old('level') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('level')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="full">
                <label for="description">Deskripsi (opsional)</label>
                <textarea id="description" name="description" placeholder="Deskripsi singkat tentang mata pelajaran ini...">{{ old('description') }}</textarea>
                <p class="help-text">Berikan penjelasan singkat tentang mata pelajaran ini.</p>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="full">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                    <label for="is_active">Aktifkan mata pelajaran ini</label>
                </div>
                <p class="help-text">Mata pelajaran yang aktif akan tersedia untuk dipilih saat membuat paket atau jadwal.</p>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-btn">Simpan Mata Pelajaran</button>
            <a href="{{ route('admin.subjects.index') }}" class="cancel-link">Batalkan</a>
        </div>
    </form>
@endsection
