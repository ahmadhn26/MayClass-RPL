@extends('tutor.layout')

@section('title', 'Tambah Materi Baru - MayClass')

@push('styles')
    <style>
        /* --- OVERLAY & MODAL STYLE (MIRIP GAMBAR) --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Gelap transparan */
            backdrop-filter: blur(5px); /* Efek Blur */
            z-index: 9999; /* Pastikan di atas segalanya */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow: hidden;
        }

        .form-card {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 900px; /* Lebar maksimal kartu */
            max-height: 90vh; /* Tinggi maksimal 90% layar */
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            animation: popIn 0.3s ease-out;
        }

        @keyframes popIn {
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Header Modal */
        .form-header {
            padding: 24px 32px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .form-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
        }

        .close-btn {
            font-size: 1.5rem;
            color: #94a3b8;
            text-decoration: none;
            line-height: 1;
            cursor: pointer;
            transition: color 0.2s;
        }
        .close-btn:hover { color: #ef4444; }

        /* Body Form (Scrollable) */
        .form-body {
            padding: 32px;
            overflow-y: auto; /* Scroll di dalam kartu */
            scrollbar-width: thin; /* Firefox */
            scrollbar-color: #cbd5e1 transparent;
        }
        
        /* Custom Scrollbar Webkit */
        .form-body::-webkit-scrollbar { width: 6px; }
        .form-body::-webkit-scrollbar-track { background: transparent; }
        .form-body::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }

        /* --- FORM GRID SYSTEM (2 KOLOM) --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Membagi 2 kolom */
            gap: 24px; /* Jarak antar kolom */
            align-items: start;
        }

        /* Kelas helper untuk elemen yang butuh lebar penuh */
        .span-full {
            grid-column: 1 / -1;
        }

        /* --- FORM ELEMENTS --- */
        label span {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="url"],
        input[type="number"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px; /* Radius lebih kecil agar elegan */
            font-family: inherit;
            font-size: 0.95rem;
            background-color: #fff;
            transition: border-color 0.2s;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #3fa67e;
            box-shadow: 0 0 0 3px rgba(63, 166, 126, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* Dynamic Groups Style */
        .dynamic-group {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            background: #f8fafc;
        }

        .dynamic-group__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .dynamic-item {
            display: grid;
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #e2e8f0;
            margin-bottom: 12px;
        }
        
        .dynamic-item__row { display: grid; gap: 12px; }
        .dynamic-item__actions { display: flex; justify-content: flex-end; }

        /* Buttons */
        .dynamic-item__remove {
            border: none; background: transparent; color: #ef4444; font-weight: 600; cursor: pointer; font-size: 0.85rem;
        }
        .dynamic-add {
            border: none; border-radius: 8px; padding: 8px 16px;
            background: rgba(63, 166, 126, 0.1); color: #3fa67e; font-weight: 600; font-size: 0.85rem; cursor: pointer;
        }

        /* Upload Field */
        .upload-field {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            color: #64748b;
            background: #f8fafc;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .upload-field:hover { border-color: #3fa67e; }

        /* Footer Actions */
        .form-actions {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 16px;
        }

        .btn-cancel {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            color: #64748b;
            background: white;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        .btn-cancel:hover { background: #f1f5f9; color: #0f172a; }

        .btn-save {
            background: #3fa67e;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(63, 166, 126, 0.3);
        }
        .btn-save:hover { background: #2f8a67; transform: translateY(-1px); }
        .btn-save:disabled { background: #9ca3af; cursor: not-allowed; }

        .error-text { color: #ef4444; font-size: 0.85rem; margin-top: 4px; }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-card { height: 100vh; max-height: 100vh; border-radius: 0; }
            .form-header { border-radius: 0; }
        }
    </style>
@endpush

@section('content')
    @php
        // Logic PHP data preparation (Tidak diubah)
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
        $chapterNextIndex = $chapterValues->keys()->max() + 1;
    @endphp

    {{-- WRAPPER OVERLAY (BLUR BACKGROUND) --}}
    <div class="modal-overlay">
        
        {{-- CARD UTAMA (FLOATING) --}}
        <div class="form-card">
            
            {{-- HEADER FORM --}}
            <div class="form-header">
                <h1>Tambah Materi Baru</h1>
                {{-- Tombol Close (X) mengarah ke index --}}
                <a href="{{ route('tutor.materials.index') }}" class="close-btn">&times;</a>
            </div>

            {{-- BODY FORM (SCROLLABLE) --}}
            <div class="form-body">
                
                {{-- Error Handling --}}
                @if ($errors->any())
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
                        <h4 style="color: #dc2626; margin: 0 0 8px 0; font-size: 0.95rem;">Terjadi kesalahan:</h4>
                        <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                            @foreach ($errors->all() as $error)
                                <li style="color: #dc2626;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('tutor.materials.store') }}" enctype="multipart/form-data" id="material-form">
                    @csrf

                    {{-- SOLUSI: Input Tersembunyi (Hidden Input) untuk 'level' --}}
                    {{-- Ini akan otomatis terisi lewat Javascript berdasarkan Mapel yang dipilih --}}
                    <input type="hidden" name="level" id="hidden-level" value="{{ old('level') }}">
                    
                    <div class="form-grid">
                        {{-- KOLOM KIRI (Paket & Judul) --}}
                        <label>
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
                            <span>Judul Materi</span>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Persamaan Linear Satu Variabel" required />
                            @error('title') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        {{-- KOLOM KANAN (Mapel & Deskripsi Full) --}}
                        <label>
                            <span>Mata Pelajaran</span>
                            <select name="subject_id" id="subject-select" data-old="{{ old('subject_id') }}" required>
                                <option value="">Pilih paket terlebih dahulu</option>
                            </select>
                            @error('subject_id') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        {{-- Span Full Width untuk Deskripsi --}}
                        <label class="span-full">
                            <span>Deskripsi Materi</span>
                            <textarea name="summary" placeholder="Jelaskan isi materi..." required>{{ old('summary') }}</textarea>
                            @error('summary') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        {{-- Input Dinamis: Tujuan Pembelajaran (Full Width) --}}
                        <div class="dynamic-group span-full">
                            <div class="dynamic-group__header">
                                <span>Tujuan Pembelajaran</span>
                                <button type="button" class="dynamic-add" data-add-objective>+ Tambah</button>
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

                        {{-- Input Dinamis: Rangkuman Bab (Full Width) --}}
                        <div class="dynamic-group span-full">
                            <div class="dynamic-group__header">
                                <span>Rangkuman Bab</span>
                                <button type="button" class="dynamic-add" data-add-chapter>+ Tambah</button>
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

                        {{-- Upload File (Full Width) --}}
                        <label class="span-full">
                            <span>Upload File (PDF, PPT, DOC)</span>
                            <div class="upload-field">
                                <input type="file" name="attachment" accept=".pdf,.ppt,.pptx,.doc,.docx" />
                                <div style="margin-top: 8px; font-size: 0.85rem;">Klik untuk upload file materi. Maksimal 10MB.</div>
                            </div>
                            @error('attachment') <div class="error-text">{{ $message }}</div> @enderror
                        </label>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="form-actions">
                        <a href="{{ route('tutor.materials.index') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-save" id="submit-btn">Simpan Materi</button>
                    </div>
                </form>
            </div> {{-- End Form Body --}}
        </div> {{-- End Form Card --}}
    </div> {{-- End Modal Overlay --}}
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const objectiveContainer = document.querySelector('[data-objectives]');
    const chapterContainer = document.querySelector('[data-chapters]');
    const packageSelect = document.getElementById('package-select');
    const subjectSelect = document.getElementById('subject-select');
    const hiddenLevelInput = document.getElementById('hidden-level'); // DOM Element untuk Hidden Input
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

    // --- AJAX Subjects Logic & Level Auto-fill ---
    if (packageSelect && subjectSelect) {
        
        // 1. Fungsi untuk mengisi Hidden Input saat Mata Pelajaran berubah
        subjectSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            // Mengambil level yang disimpan di atribut data-level
            const level = selectedOption.getAttribute('data-level') || '';
            if (hiddenLevelInput) {
                hiddenLevelInput.value = level;
            }
        });

        const loadSubjects = (packageId, oldSelection = null) => {
            subjectSelect.innerHTML = '<option value="">Memuat mata pelajaran...</option>';
            subjectSelect.disabled = true;

            if (!packageId) {
                subjectSelect.innerHTML = '<option value="">Pilih paket terlebih dahulu</option>';
                subjectSelect.disabled = true;
                return;
            }

            fetch(/tutor/packages/${packageId}/subjects)
                .then(res => res.json())
                .then(data => {
                    subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                    if (data && data.length > 0) {
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            // SIMPAN DATA LEVEL DI SINI (Tersembunyi dalam atribut HTML)
                            option.setAttribute('data-level', subject.level || ''); 
                            
                            option.textContent = ${subject.name} (${subject.level});
                            
                            if (oldSelection && String(subject.id) === String(oldSelection)) {
                                option.selected = true;
                                // Jika ini adalah old selection (saat reload error), isi juga hidden input
                                if (hiddenLevelInput) hiddenLevelInput.value = subject.level || '';
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

        packageSelect.addEventListener('change', function() {
            loadSubjects(this.value);
            // Reset level jika paket berubah
            if(hiddenLevelInput) hiddenLevelInput.value = '';
        });

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