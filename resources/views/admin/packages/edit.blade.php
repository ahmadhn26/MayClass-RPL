@extends('admin.layout')

@section('title', 'Ubah Paket - MayClass')

@push('styles')
    <style>
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 26px;
            padding: 28px 32px;
            border: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
            max-width: 780px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
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
        textarea {
            width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            padding: 12px 14px;
            font-size: 0.95rem;
            font-family: inherit;
            background: rgba(248, 250, 252, 0.9);
        }

        textarea {
            min-height: 140px;
            resize: vertical;
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
        }

        .error-message {
            margin-top: 6px;
            color: #dc2626;
            font-size: 0.85rem;
        }

        .help-text {
            margin: 6px 0 12px;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .subject-selection {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 12px;
        }

        .subject-group h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 10px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(15, 23, 42, 0.1);
        }

        .subject-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            background: rgba(248, 250, 252, 0.5);
            border: 1px solid rgba(15, 23, 42, 0.08);
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .checkbox-label:hover {
            background: rgba(248, 250, 252, 1);
            border-color: rgba(15, 23, 42, 0.15);
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin: 0;
        }

        /* Pricing Features Styles */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 12px;
        }

        .feature-item {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .feature-item input {
            flex: 1;
        }

        .btn-remove {
            padding: 10px 16px;
            border-radius: 10px;
            border: none;
            background: #fee2e2;
            color: #b91c1c;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .btn-add {
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
            background: rgba(31, 209, 161, 0.15);
            color: #0f766e;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 8px;
        }

        .btn-add:hover {
            background: rgba(31, 209, 161, 0.25);
        }

        .btn-remove:hover {
            background: #fecaca;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .subject-checkboxes {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.packages.index') }}" class="cancel-link" style="margin-bottom: 22px;">Kembali ke daftar paket</a>

    @php($stageOptions = $stages ?? [])

    <form action="{{ route('admin.packages.update', $package) }}" method="POST" class="form-card">
        @csrf
        @method('PUT')
        <h2 style="margin-top: 0; font-size: 1.6rem;">Ubah Paket Belajar</h2>
        <p style="color: var(--text-muted); margin-top: 6px;">Perbarui detail paket untuk menjaga konsistensi informasi.</p>

        <div class="form-grid">
            <div>
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $package->slug) }}" required />
                @error('slug')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="level">Jenjang Pendidikan</label>
                <select id="level" name="level" required style="width: 100%; border-radius: 14px; border: 1px solid rgba(15, 23, 42, 0.12); padding: 12px 14px; font-size: 0.95rem; background: rgba(248, 250, 252, 0.9);">
                    <option value="" disabled {{ old('level', $package->level) ? '' : 'selected' }}>Pilih jenjang</option>
                    @foreach ($stageOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('level', $package->level) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('level')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="grade_range">Rentang Kelas</label>
                <input type="text" id="grade_range" name="grade_range" value="{{ old('grade_range', $package->grade_range) }}" placeholder="Contoh: Kelas 10 - 12 &amp; UTBK" required />
                @error('grade_range')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="tag">Tag</label>
                <input type="text" id="tag" name="tag" value="{{ old('tag', $package->tag) }}" />
                @error('tag')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="price">Harga (numerik)</label>
                <input type="number" id="price" name="price" value="{{ old('price', $package->price) }}" min="0" step="1000" required />
                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="max_students">Kuota Maksimum (opsional)</label>
                <input
                    type="number"
                    id="max_students"
                    name="max_students"
                    value="{{ old('max_students', $package->max_students) }}"
                    min="1"
                    step="1"
                    placeholder="Contoh: 25"
                />
                <p style="margin: 6px 0 0; color: var(--text-muted); font-size: 0.85rem;">Biarkan kosong jika kuota tidak dibatasi.</p>
                @error('max_students')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="card_price_label">Label Harga Kartu</label>
                <input type="text" id="card_price_label" name="card_price_label" value="{{ old('card_price_label', $package->card_price_label) }}" required />
                @error('card_price_label')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="detail_price_label">Label Harga Detail</label>
                <input type="text" id="detail_price_label" name="detail_price_label" value="{{ old('detail_price_label', $package->detail_price_label) }}" required />
                @error('detail_price_label')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="full">
                <label for="detail_title">Judul Paket</label>
                <input type="text" id="detail_title" name="detail_title" value="{{ old('detail_title', $package->detail_title) }}" required />
                @error('detail_title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="full">
                <label for="image_url">Kunci Gambar (unsplash)</label>
                <input type="text" id="image_url" name="image_url" value="{{ old('image_url', $package->image_url) }}" required />
                @error('image_url')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="full">
                <label for="summary">Ringkasan</label>
                <textarea id="summary" name="summary" required>{{ old('summary', $package->summary) }}</textarea>
                @error('summary')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="full">
                <label>Fitur Pricing (Ditampilkan di Kartu Paket)</label>
                <p class="help-text">Tambahkan fitur-fitur yang akan ditampilkan pada kartu paket di landing page. Contoh: "6x kelas live per bulan", "Tryout AKM & evaluasi mendalam"</p>
                
                <div class="feature-list" id="feature-list">
                    <?php
                    $existingFeatures = $package->cardFeatures->pluck('label')->toArray();
                    $oldFeatures = old('card_features', $existingFeatures);
                    if (empty($oldFeatures)) {
                        $oldFeatures = [''];
                    }
                    ?>
                    @foreach($oldFeatures as $index => $feature)
                        <div class="feature-item">
                            <input type="text" name="card_features[]" value="{{ $feature }}" placeholder="Contoh: 6x kelas live per bulan" />
                            <button type="button" class="btn-remove" onclick="removeFeature(this)">Hapus</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add" onclick="addFeature()">+ Tambah Fitur</button>
                
                @error('card_features')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="full">
                <label>Mata Pelajaran yang Termasuk *</label>
                <p class="help-text">Pilih minimal 1 mata pelajaran yang akan diajarkan dalam paket ini.</p>
                
                <div class="subject-selection">
                    @foreach(['SD', 'SMP', 'SMA'] as $level)
                        @if($subjectsByLevel[$level]->isNotEmpty())
                            <div class="subject-group">
                                <h4>{{ $level }}</h4>
                                <div class="subject-checkboxes">
                                    @foreach($subjectsByLevel[$level] as $subject)
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                                {{ in_array($subject->id, old('subjects', $package->subjects->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            {{ $subject->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                @error('subjects')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="full">
                <label>Tutor Pengampu (Opsional)</label>
                <p class="help-text">Pilih tutor yang ditugaskan untuk mengajar paket ini. Tutor yang dipilih akan dapat membuat jadwal untuk paket ini.</p>
                
                <div class="subject-selection">
                    @if($tutors->isEmpty())
                        <div class="empty-state">Belum ada tutor aktif.</div>
                    @else
                        <div class="subject-group">
                            <h4>Daftar Tutor</h4>
                            <div class="subject-checkboxes">
                                @foreach($tutors as $tutor)
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="tutors[]" value="{{ $tutor->id }}" 
                                            {{ in_array($tutor->id, old('tutors', $package->tutors->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        {{ $tutor->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-btn">Simpan Perubahan</button>
            <a href="{{ route('admin.packages.index') }}" class="cancel-link">Batalkan</a>
        </div>
    </form>

    <script>
        function addFeature() {
            const list = document.getElementById('feature-list');
            const item = document.createElement('div');
            item.className = 'feature-item';
            item.innerHTML = `
                <input type="text" name="card_features[]" placeholder="Contoh: 6x kelas live per bulan" />
                <button type="button" class="btn-remove" onclick="removeFeature(this)">Hapus</button>
            `;
            list.appendChild(item);
        }

        function removeFeature(button) {
            const list = document.getElementById('feature-list');
            if (list.children.length > 1) {
                button.parentElement.remove();
            } else {
                // Keep at least one input, just clear it
                button.parentElement.querySelector('input').value = '';
            }
        }
    </script>
@endsection
