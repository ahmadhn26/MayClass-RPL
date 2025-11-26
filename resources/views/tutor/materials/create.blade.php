@extends('tutor.layout')

@section('title', 'Tambah Materi Baru - MayClass')

@push('styles')
    <style>
        .form-card {
            background: #fff;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 22px 55px rgba(15, 23, 42, 0.08);
        }

        .form-card h1 { margin-top: 0; }

        .form-grid {
            display: grid;
            gap: 18px;
            margin-top: 24px;
        }

        label span {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="url"],
        input[type="number"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #d9e0ea;
            border-radius: 16px;
            font-family: inherit;
            font-size: 1rem;
            background-color: #fff;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .dynamic-group {
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 18px;
            background: rgba(249, 250, 251, 0.8);
        }

        .dynamic-group__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .dynamic-group__header span {
            font-weight: 600;
            font-size: 1rem;
        }

        .dynamic-group__items {
            display: grid;
            gap: 12px;
        }

        .dynamic-item {
            display: grid;
            gap: 12px;
            padding: 14px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid #e5e7eb;
        }
        
        .dynamic-item__row {
            display: grid;
            gap: 12px;
        }

        .dynamic-item__actions {
            display: flex;
            justify-content: flex-end;
        }

        .dynamic-item__remove {
            appearance: none;
            border: none;
            background: transparent;
            color: #ef4444;
            font-weight: 600;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .dynamic-item__remove:hover { background: #fef2f2; }

        .dynamic-add {
            appearance: none;
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            background: rgba(63, 166, 126, 0.12); /* Hijau Muda */
            color: #3fa67e; /* Hijau Tema */
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dynamic-add:hover { background: rgba(63, 166, 126, 0.2); }

        .upload-field {
            border: 2px dashed #d9e0ea;
            border-radius: 18px;
            padding: 28px;
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 6px;
        }

        /* --- STYLE TOMBOL --- */
        .form-actions {
            display: flex;
            gap: 16px;
            margin-top: 32px;
            align-items: center;
            justify-content: flex-end;
        }

        .btn-cancel {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            color: #64748b;
            background: #f1f5f9;
            border: 1px solid transparent;
            transition: all 0.2s;
            font-size: 1rem;
            display: inline-block;
        }

        .btn-cancel:hover { background: #e2e8f0; color: #0f172a; }

        .btn-save {
            background: #3fa67e; /* HIJAU TEMA */
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(63, 166, 126, 0.3);
        }

        .btn-save:hover {
            background: #2f8a67;
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -1px rgba(63, 166, 126, 0.4);
        }
        
        .btn-save:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
    </style>
@endpush

@section('content')
    @php
        // Logic PHP untuk menyiapkan data input dinamis (Objectives & Chapters)
        $objectiveValues = collect(old('objectives', ['']))->map(fn($value) => is_string($value) ? $value : '');
        if ($objectiveValues->isEmpty()) $objectiveValues = collect(['']);

        $chapterValues = collect(old('chapters', [['title' => '', 'description' => '']]))
            ->map(function ($chapter) {
                return [
                    'title' => is_array($chapter) ? ($chapter['title'] ?? '') : '',
                    'description' => is_array($chapter) ? ($chapter['description'] ?? '') : '',
                ];
            });
        if ($chapterValues->isEmpty()) $chapterValues = collect([['title' => '', 'description' => '']]);
        
        // Menentukan index selanjutnya untuk chapter agar tidak bentrok saat tambah baru
        $chapterNextIndex = $chapterValues->keys()->max() + 1;
    @endphp

    <div class="form-card">
        <h1>Tambah Materi Baru</h1>
        <p>Buat materi pembelajaran untuk siswa MayClass.</p>

        {{-- Tampilkan Error Umum jika ada --}}
        @if ($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                <h4 style="color: #dc2626; margin: 0 0 8px 0;">Terjadi kesalahan:</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li style="color: #dc2626;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tutor.materials.store') }}" enctype="multipart/form-data" class="form-grid" id="material-form">
            @csrf
            
            <label>
                {{-- PERBAIKAN: Bintang merah dihapus --}}
                <span>Paket Belajar</span>
                <select name="package_id" id="package-select" required>
                    <option value="">Pilih paket yang tersedia</option>
                    @forelse ($packages as $package)
                        <option value="{{ $package->id }}" @selected(old('package_id') == $package->id)>
                            {{ $package->detail_title ?? $package->title }}
                        </option>
                    @empty
                        <option value="" disabled>Belum ada paket yang tersedia</option>
                    @endforelse
                </select>
                @error('package_id') <div class="error-text">{{ $message }}</div> @enderror
            </label>

            <label>
                <span>Judul Materi <span style="color: #dc2626;">*</span></span>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Persamaan Linear Satu Variabel" required />
                @error('title') <div class="error-text">{{ $message }}</div> @enderror
            </label>

            <label>
                <span>Mata Pelajaran <span style="color: #dc2626;">*</span></span>
                {{-- SELECT dengan data-old untuk AJAX fix --}}
                <select name="subject_id" id="subject-select" data-old="{{ old('subject_id') }}" required>
                    <option value="">Pilih paket terlebih dahulu</option>
                </select>
                @error('subject_id') <div class="error-text">{{ $message }}</div> @enderror
            </label>

            <label>
                <span>Kelas <span style="color: #dc2626;">*</span></span>
                <input type="text" name="level" value="{{ old('level') }}" placeholder="Contoh: Kelas 10A" required />
                @error('level') <div class="error-text">{{ $message }}</div> @enderror
            </label>

            <label>
                <span>Deskripsi Materi <span style="color: #dc2626;">*</span></span>
                <textarea name="summary" placeholder="Jelaskan isi materi..." required>{{ old('summary') }}</textarea>
                @error('summary') <div class="error-text">{{ $message }}</div> @enderror
            </label>

            {{-- Input Dinamis: Tujuan Pembelajaran --}}
            <div class="dynamic-group">
                <div class="dynamic-group__header">
                    <span>Tujuan Pembelajaran</span>
                    <button type="button" class="dynamic-add" data-add-objective>Tambah tujuan</button>
                </div>
                <div class="dynamic-group__items" data-objectives>
                    @foreach ($objectiveValues as $value)
                        <div class="dynamic-item" data-objective-row>
                            <input type="text" name="objectives[]" value="{{ $value }}" placeholder="Contoh: Memahami konsep persamaan linear" />
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Input Dinamis: Rangkuman Bab --}}
            <div class="dynamic-group">
                <div class="dynamic-group__header">
                    <span>Rangkuman Bab</span>
                    <button type="button" class="dynamic-add" data-add-chapter>Tambah bab</button>
                </div>
                <div class="dynamic-group__items" data-chapters data-next-index="{{ $chapterNextIndex }}">
                    @foreach ($chapterValues as $index => $chapter)
                        <div class="dynamic-item" data-chapter-row>
                            <div class="dynamic-item__row">
                                <input type="text" name="chapters[{{ $index }}][title]" value="{{ $chapter['title'] }}" placeholder="Judul bab" />
                                <textarea name="chapters[{{ $index }}][description]" placeholder="Ringkasan singkat bab" style="min-height: 80px;">{{ $chapter['description'] }}</textarea>
                            </div>
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <label>
                <span>Upload File (PDF, PPT, DOC)</span>
                <div class="upload-field">
                    <input type="file" name="attachment" accept=".pdf,.ppt,.pptx,.doc,.docx" />
                    <div style="margin-top: 8px; font-size: 0.85rem;">Ukuran maksimal 10MB.</div>
                </div>
                @error('attachment') <div class="error-text">{{ $message }}</div> @enderror
            </label>

            <div class="form-actions">
                <a href="{{ route('tutor.materials.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save" id="submit-btn">Simpan Materi</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const objectiveContainer = document.querySelector('[data-objectives]');
    const chapterContainer = document.querySelector('[data-chapters]');
    const packageSelect = document.getElementById('package-select');
    const subjectSelect = document.getElementById('subject-select');
    const form = document.getElementById('material-form');
    const submitBtn = document.getElementById('submit-btn');

    // --- Logic Dynamic Inputs (Objectives) ---
    const templateObjective = () => {
        const wrapper = document.createElement('div');
        wrapper.className = 'dynamic-item';
        wrapper.innerHTML = `
            <input type="text" name="objectives[]" placeholder="Contoh: Memahami konsep persamaan linear" />
            <div class="dynamic-item__actions">
                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
            </div>
        `;
        return wrapper;
    };

    // --- Logic Dynamic Inputs (Chapters) ---
    const templateChapter = (index) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'dynamic-item';
        wrapper.innerHTML = `
            <div class="dynamic-item__row">
                <input type="text" name="chapters[${index}][title]" placeholder="Judul bab" />
                <textarea name="chapters[${index}][description]" placeholder="Ringkasan singkat bab" style="min-height: 80px;"></textarea>
            </div>
            <div class="dynamic-item__actions">
                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
            </div>
        `;
        return wrapper;
    };

    // Helper: Bind Remove Button
    const bindRemoval = (row) => {
        row.querySelector('[data-remove-row]')?.addEventListener('click', function() {
            if (row.parentElement.children.length > 1) row.remove();
        });
    };

    // Initialize Removal on existing rows
    document.querySelectorAll('[data-remove-row]').forEach(btn => {
        bindRemoval(btn.closest('.dynamic-item'));
    });

    // Event Add Objective
    document.querySelector('[data-add-objective]')?.addEventListener('click', (e) => {
        e.preventDefault();
        if (!objectiveContainer) return;
        const row = templateObjective();
        objectiveContainer.appendChild(row);
        bindRemoval(row);
    });

    // Event Add Chapter
    const addChapterBtn = document.querySelector('[data-add-chapter]');
    let nextChapterIndex = Number(chapterContainer?.dataset.nextIndex || 0);

    addChapterBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        if (!chapterContainer) return;
        const row = templateChapter(nextChapterIndex++);
        chapterContainer.appendChild(row);
        bindRemoval(row);
    });

    // --- AJAX Subjects Logic (Fix Reset Issue) ---
    if (packageSelect && subjectSelect) {
        const loadSubjects = (packageId, oldSelection = null) => {
            subjectSelect.innerHTML = '<option value="">Memuat mata pelajaran...</option>';
            subjectSelect.disabled = true;

            if (!packageId) {
                subjectSelect.innerHTML = '<option value="">Pilih paket terlebih dahulu</option>';
                subjectSelect.disabled = true;
                return;
            }

            fetch(`/tutor/packages/${packageId}/subjects`)
                .then(res => res.json())
                .then(data => {
                    subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                    if (data && data.length > 0) {
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = `${subject.name} (${subject.level})`;
                            // Logic Pilih Kembali Data Lama
                            if (oldSelection && String(subject.id) === String(oldSelection)) {
                                option.selected = true;
                            }
                            subjectSelect.appendChild(option);
                        });
                    } else {
                        subjectSelect.innerHTML = '<option value="">Tidak ada mapel</option>';
                    }
                    subjectSelect.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    subjectSelect.innerHTML = '<option value="">Gagal memuat</option>';
                    subjectSelect.disabled = false;
                });
        };

        // Listen Change
        packageSelect.addEventListener('change', function() {
            loadSubjects(this.value);
        });

        // Trigger on Load (Validation Error Fix)
        if (packageSelect.value) {
            const oldSubjectId = subjectSelect.getAttribute('data-old');
            loadSubjects(packageSelect.value, oldSubjectId);
        }
    }

    // Disable Submit Button on Click
    form?.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';
    });
});
</script>
@endpush