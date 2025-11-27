@extends('tutor.layout')

@section('title', 'Edit Materi - MayClass')

@push('styles')
    <style>
        /* --- OVERLAY & MODAL STYLE --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 9999;
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
            max-width: 900px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            animation: popIn 0.3s ease-out;
        }

        @keyframes popIn {
            0% {
                transform: scale(0.95);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .form-header {
            padding: 24px 32px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
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

        .close-btn:hover {
            color: #ef4444;
        }

        .form-body {
            padding: 32px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .form-body::-webkit-scrollbar {
            width: 6px;
        }

        .form-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .form-body::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        /* --- GRID SYSTEM --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .span-full {
            grid-column: 1 / -1;
        }

        /* --- INPUTS --- */
        label span {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.95rem;
            background-color: #fff;
            transition: border-color 0.2s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #3fa67e;
            box-shadow: 0 0 0 3px rgba(63, 166, 126, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* --- DYNAMIC GROUPS --- */
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

        .dynamic-item__row {
            display: grid;
            gap: 12px;
        }

        .dynamic-item__actions {
            display: flex;
            justify-content: flex-end;
        }

        .dynamic-item__remove {
            border: none;
            background: transparent;
            color: #ef4444;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .dynamic-add {
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            background: rgba(63, 166, 126, 0.1);
            color: #3fa67e;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
        }

        /* --- UPLOAD & FILE INFO --- */
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

        .upload-field:hover {
            border-color: #3fa67e;
        }

        .current-file {
            margin-top: 12px;
            padding: 10px 16px;
            background: #f1f5f9;
            border-radius: 8px;
            display: inline-block;
            font-size: 0.9rem;
            color: #475569;
        }

        .current-file a {
            color: #3fa67e;
            font-weight: 600;
            text-decoration: none;
        }

        .current-file a:hover {
            text-decoration: underline;
        }

        /* --- ACTIONS --- */
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

        .btn-cancel:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

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

        .btn-save:hover {
            background: #2f8a67;
            transform: translateY(-1px);
        }

        .error-text {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        /* Package Multi-Select Styling */
        .package-checkbox-edit:checked + label {
            border-color: #3fa67e !important;
            background: rgba(63, 166, 126, 0.1) !important;
        }

        .package-checkbox-edit:checked + label::before {
            content: '✓';
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: #3fa67e;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .package-checkbox-edit + label:hover {
            border-color: #3fa67e !important;
            background: rgba(63, 166, 126, 0.05) !important;
        }

        .package-checkbox-edit:checked + label span:last-child {
            background: rgba(63, 166, 126, 0.15) !important;
            color: #3fa67e !important;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-card {
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
            }

            .form-header {
                border-radius: 0;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $objectiveValues = collect(old('objectives', $material->objectives->pluck('description')->all()))
            ->map(fn($value) => is_string($value) ? $value : '');
        if ($objectiveValues->isEmpty())
            $objectiveValues = collect(['']);

        $chapterValues = collect(old('chapters', $material->chapters->map(fn($chapter) => [
            'title' => $chapter->title,
            'description' => $chapter->description,
        ])->all()))
            ->map(function ($chapter) {
                return [
                    'title' => is_array($chapter) ? ($chapter['title'] ?? '') : '',
                    'description' => is_array($chapter) ? ($chapter['description'] ?? '') : '',
                ];
            });

        if ($chapterValues->isEmpty())
            $chapterValues = collect([['title' => '', 'description' => '']]);

        $chapterNextIndex = $chapterValues->keys()->max() + 1;

        // Prepare GDrive Links
        $gdriveLinks = collect(old('gdrive_links', $material->resource_url ?? []));
        if ($gdriveLinks->isEmpty())
            $gdriveLinks = collect(['']);

        // Prepare Quiz URLs
        $quizUrls = collect(old('quiz_urls', $material->quiz_urls ?? []));
        if ($quizUrls->isEmpty())
            $quizUrls = collect(['']);
    @endphp

    <div class="modal-overlay">
        <div class="form-card">

            <div class="form-header">
                <h1>Edit Materi</h1>
                <a href="{{ route('tutor.materials.index') }}" class="close-btn">&times;</a>
            </div>

            <div class="form-body">
                @if ($errors->any())
                    <div
                        style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
                        <h4 style="color: #dc2626; margin: 0 0 8px 0; font-size: 0.95rem;">Terjadi kesalahan:</h4>
                        <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                            @foreach ($errors->all() as $error)
                                <li style="color: #dc2626;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('tutor.materials.update', $material) }}" enctype="multipart/form-data"
                    id="material-form">
                    @csrf
                    @method('PUT')

                    {{-- 🟢 PERBAIKAN: Menambahkan Hidden Input Level --}}
                    {{-- Nilai diambil dari old input atau relasi subject level saat ini --}}
                    <input type="hidden" name="level" id="hidden-level"
                        value="{{ old('level', $material->subject->level ?? '') }}">

                    <div class="form-grid">
                        <label>
                            <span>Pilih Paket Belajar (bisa lebih dari 1)</span>
                            <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; background: white; max-height: 250px; overflow-y: auto;">
                                @forelse ($packages as $package)
                                    <div style="margin-bottom: 8px;">
                                        <input 
                                            type="checkbox" 
                                            name="package_ids[]" 
                                            id="edit_package_{{ $package->id }}" 
                                            value="{{ $package->id }}" 
                                            data-level="{{ $package->level }}"
                                            class="package-checkbox-edit"
                                            onchange="handleEditPackageSelection()"
                                            {{ in_array($package->id, $selectedPackageIds) ? 'checked' : '' }}
                                            style="display: none;">
                                        <label for="edit_package_{{ $package->id }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; cursor: pointer; transition: all 0.2s; position: relative;">
                                            <span style="font-weight: 600; color: #1e293b;">{{ $package->detail_title ?? $package->title }}</span>
                                            <span style="font-size: 0.85rem; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; margin-right: 28px;">{{ $package->level }}</span>
                                        </label>
                                    </div>
                                @empty
                                    <div style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada paket yang tersedia</div>
                                @endforelse
                            </div>
                            @error('package_ids') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        <label>
                            <span>Judul Materi</span>
                            <input type="text" name="title" value="{{ old('title', $material->title) }}" required />
                            @error('title') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        <label>
                            <span>Mata Pelajaran</span>
                            <select name="subject_id" id="subject-select" required>>
                                <option value="">Memuat...</option>
                            </select>
                            @error('subject_id') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        {{-- FORM KELAS DIHAPUS (Data dikirim via hidden input di atas) --}}

                        <label class="span-full">
                            <span>Deskripsi Singkat</span>
                            <textarea name="summary" required>{{ old('summary', $material->summary) }}</textarea>
                            @error('summary') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        <div class="dynamic-group span-full">
                            <div class="dynamic-group__header">
                                <span>Tujuan Pembelajaran</span>
                                <button type="button" class="dynamic-add" data-add-objective>+ Tambah</button>
                            </div>
                            <div class="dynamic-group__items" data-objectives>
                                @foreach ($objectiveValues as $value)
                                    <div class="dynamic-item" data-objective-row>
                                        <div class="dynamic-item__row">
                                            <input type="text" name="objectives[]" value="{{ $value }}"
                                                placeholder="Contoh: Memahami konsep persamaan linear" />
                                        </div>
                                        <div class="dynamic-item__actions">
                                            <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('objectives.*') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="dynamic-group span-full">
                            <div class="dynamic-group__header">
                                <span>Rangkuman Bab</span>
                                <button type="button" class="dynamic-add" data-add-chapter>+ Tambah</button>
                            </div>
                            <div class="dynamic-group__items" data-chapters data-next-index="{{ $chapterNextIndex }}">
                                @foreach ($chapterValues as $index => $chapter)
                                    <div class="dynamic-item" data-chapter-row>
                                        <div class="dynamic-item__row">
                                            <input type="text" name="chapters[{{ $index }}][title]"
                                                value="{{ $chapter['title'] }}" placeholder="Judul bab" />
                                            <textarea name="chapters[{{ $index }}][description]"
                                                placeholder="Ringkasan singkat bab">{{ $chapter['description'] }}</textarea>
                                        </div>
                                        <div class="dynamic-item__actions">
                                            <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('chapters.*') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="dynamic-group span-full">
                            <div class="dynamic-group__header">
                                <span>Link Materi (Google Drive)</span>
                                <button type="button" class="dynamic-add" data-add-gdrive>+ Tambah Link</button>
                            </div>
                            <div class="dynamic-group__items" data-gdrive-links>
                                @foreach ($gdriveLinks as $link)
                                    <div class="dynamic-item">
                                        <div class="dynamic-item__row">
                                            <input type="url" name="gdrive_links[]" value="{{ $link }}"
                                                placeholder="https://drive.google.com/..." required />
                                        </div>
                                        <div class="dynamic-item__actions">
                                            <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('gdrive_links.*') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="dynamic-group span-full">
                            <div class="dynamic-group__header">
                                <span>Link Quiz</span>
                                <button type="button" class="dynamic-add" data-add-quiz>+ Tambah Quiz</button>
                            </div>
                            <div class="dynamic-group__items" data-quiz-urls>
                                @foreach ($quizUrls as $link)
                                    <div class="dynamic-item">
                                        <div class="dynamic-item__row">
                                            <input type="url" name="quiz_urls[]" value="{{ $link }}"
                                                placeholder="https://forms.google.com/..." />
                                        </div>
                                        <div class="dynamic-item__actions">
                                            <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('quiz_urls.*') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('tutor.materials.index') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-save">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const objectiveContainer = document.querySelector('[data-objectives]');
            const chapterContainer = document.querySelector('[data-chapters]');
            const packageSelect = document.getElementById('package-select');
            const subjectSelect = document.getElementById('subject-select');
            const hiddenLevelInput = document.getElementById('hidden-level'); // 🟢 Ambil elemen hidden input
            const currentSubjectId = "{{ old('subject_id', $material->subject_id) }}";

            // --- Logic Dynamic Inputs (Objectives) ---
            const templateObjective = () => {
                const wrapper = document.createElement('div');
                wrapper.className = 'dynamic-item';
                wrapper.innerHTML = `
                            <div class="dynamic-item__row">
                                <input type="text" name="objectives[]" placeholder="Contoh: Memahami konsep persamaan linear" />
                            </div>
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
                                <textarea name="chapters[${index}][description]" placeholder="Ringkasan singkat bab"></textarea>
                            </div>
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        `;
                return wrapper;
            };

            // Helper: Bind Remove Button
            const bindRemoval = (row) => {
                row.querySelector('[data-remove-row]')?.addEventListener('click', function () {
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

            // --- Logic Dynamic Inputs (GDrive & Quiz) ---
            const gdriveContainer = document.querySelector('[data-gdrive-links]');
            const quizContainer = document.querySelector('[data-quiz-urls]');

            const templateLink = (name, placeholder) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'dynamic-item';
                wrapper.innerHTML = `
                            <div class="dynamic-item__row">
                                <input type="url" name="${name}[]" placeholder="${placeholder}" required />
                            </div>
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        `;
                return wrapper;
            };

            document.querySelector('[data-add-gdrive]')?.addEventListener('click', (e) => {
                e.preventDefault();
                if (!gdriveContainer) return;
                const row = templateLink('gdrive_links', 'https://drive.google.com/...');
                gdriveContainer.appendChild(row);
                bindRemoval(row);
            });

            document.querySelector('[data-add-quiz]')?.addEventListener('click', (e) => {
                e.preventDefault();
                if (!quizContainer) return;
                const row = templateLink('quiz_urls', 'https://forms.google.com/...');
                quizContainer.appendChild(row);
                bindRemoval(row);
            });

            // 🟢 PERBAIKAN: Listener saat Mapel Berubah untuk update Hidden Level
            if (subjectSelect) {
                subjectSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const level = selectedOption.getAttribute('data-level') || ''; // Ambil data level
                    if (hiddenLevelInput) {
                        hiddenLevelInput.value = level; // Isi ke hidden input
                    }
                });
            }

            // Handle Package Multi-Selection in Edit Form
            window.handleEditPackageSelection = function() {
                const checkboxes = document.querySelectorAll('.package-checkbox-edit:checked');
                
                if (checkboxes.length > 0) {
                    // Get first selected package to fetch subjects
                    const firstPackage = checkboxes[0];
                    const packageId = firstPackage.value;
                    const packageLevel = firstPackage.dataset.level;
                    
                    // Update level
                    if (hiddenLevelInput) {
                        hiddenLevelInput.value = packageLevel;
                    }
                    
                    // Fetch subjects for first selected package
                    loadSubjects(packageId, currentSubjectId);
                } else {
                    // No package selected
                    subjectSelect.innerHTML = '<option value="">-- Pilih Paket Dulu --</option>';
                    subjectSelect.disabled = true;
                    
                    if (hiddenLevelInput) {
                        hiddenLevelInput.value = '';
                    }
                }
            };

            // --- AJAX Subject Dropdown ---
            const loadSubjects = (packageId, selectedId = null) => {
                subjectSelect.innerHTML = '<option value="">Memuat...</option>';
                subjectSelect.disabled = true;

                if (packageId) {
                    fetch(`/tutor/packages/${packageId}/subjects`)
                        .then(response => response.json())
                        .then(data => {
                            subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                            data.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;

                                // 🟢 PERBAIKAN: Simpan Level di Atribut Data
                                option.setAttribute('data-level', subject.level || '');

                                option.textContent = subject.name + ' (' + subject.level + ')';

                                if (selectedId && String(subject.id) === String(selectedId)) {
                                    option.selected = true;
                                    // 🟢 PERBAIKAN: Jika ini mapel terpilih saat load, isi hidden input
                                    if (hiddenLevelInput) hiddenLevelInput.value = subject.level || '';
                                }
                                subjectSelect.appendChild(option);
                            });
                            subjectSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            subjectSelect.innerHTML = '<option value="">Gagal memuat mata pelajaran</option>';
                        });
                } else {
                    subjectSelect.innerHTML = '<option value="">Pilih paket terlebih dahulu</option>';
                    subjectSelect.disabled = true;
                }
            };

            // Initial load - get first checked package
            const firstCheckedPackage = document.querySelector('.package-checkbox-edit:checked');
            if (firstCheckedPackage) {
                loadSubjects(firstCheckedPackage.value, currentSubjectId);
            }
        });
    </script>
@endpush