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

        .form-card h1 {
            margin-top: 0;
        }

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
        input[type="email"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #d9e0ea;
            border-radius: 16px;
            font-family: inherit;
            font-size: 1rem;
        }

        textarea {
            min-height: 140px;
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
        }

        .dynamic-add {
            appearance: none;
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            background: rgba(61, 183, 173, 0.12);
            color: var(--primary-dark);
            font-weight: 600;
            cursor: pointer;
        }

        .upload-field {
            border: 2px dashed #d9e0ea;
            border-radius: 18px;
            padding: 28px;
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .form-actions {
            display: flex;
            gap: 16px;
            margin-top: 28px;
        }

        .form-actions a,
        .form-actions button {
            padding: 14px 24px;
            border-radius: 16px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
        }

        .form-actions a {
            border: 1px solid #d9e0ea;
            color: #1f2937;
        }

        .form-actions button {
            border: none;
            background: var(--primary);
            color: #fff;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 6px;
        }
    </style>
@endpush

@section('content')
    @php
        $objectiveValues = collect(old('objectives', ['']))->map(fn($value) => is_string($value) ? $value : '');
        if ($objectiveValues->isEmpty()) {
            $objectiveValues = collect(['']);
        }

        $chapterValues = collect(old('chapters', [['title' => '', 'description' => '']]))
            ->map(function ($chapter) {
                return [
                    'title' => is_array($chapter) ? ($chapter['title'] ?? '') : '',
                    'description' => is_array($chapter) ? ($chapter['description'] ?? '') : '',
                ];
            });

        if ($chapterValues->isEmpty()) {
            $chapterValues = collect([['title' => '', 'description' => '']]);
        }

        $chapterNextIndex = $chapterValues->keys()->map(fn ($key) => (int) $key)->max();
        $chapterNextIndex = is_null($chapterNextIndex) ? $chapterValues->count() : $chapterNextIndex + 1;
    @endphp
    <div class="form-card">
        <h1>Tambah Materi Baru</h1>
        <p>Buat materi pembelajaran untuk siswa MayClass.</p>

        <form method="POST" action="{{ route('tutor.materials.store') }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            <label>
                <span>Paket Belajar</span>
                <select name="package_id" required style="width: 100%; padding: 14px 18px; border: 1px solid #d9e0ea; border-radius: 16px; font-family: inherit; font-size: 1rem;">
                    <option value="">Pilih paket yang tersedia</option>
                    @forelse ($packages as $package)
                        <option value="{{ $package->id }}" @selected(old('package_id') == $package->id)>
                            {{ $package->detail_title ?? $package->title }}
                        </option>
                    @empty
                        <option value="" disabled>Belum ada paket yang tersedia</option>
                    @endforelse
                </select>
                @error('package_id')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Judul Materi</span>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Persamaan Linear Satu Variabel" required />
                @error('title')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Mata Pelajaran</span>
                <select name="subject_id" id="subject-select" required style="width: 100%; padding: 14px 18px; border: 1px solid #d9e0ea; border-radius: 16px; font-family: inherit; font-size: 1rem;">
                    <option value="">Pilih paket terlebih dahulu</option>
                </select>
                @error('subject_id')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Kelas</span>
                <input type="text" name="level" value="{{ old('level') }}" placeholder="Contoh: Kelas 10A" required />
                @error('level')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Deskripsi Materi</span>
                <textarea name="summary" placeholder="Jelaskan isi materi..." required>{{ old('summary') }}</textarea>
                @error('summary')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <div class="dynamic-group">
                <div class="dynamic-group__header">
                    <span>Tujuan Pembelajaran</span>
                    <button class="dynamic-add" data-add-objective>Tambah tujuan</button>
                </div>
                <div class="dynamic-group__items" data-objectives>
                    @foreach ($objectiveValues as $value)
                        <div class="dynamic-item" data-objective-row>
                            <div class="dynamic-item__row">
                                <input type="text" name="objectives[]" value="{{ $value }}" placeholder="Contoh: Memahami konsep persamaan linear" />
                            </div>
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @php
                    $objectiveError = $errors->first('objectives') ?: $errors->first('objectives.*');
                @endphp
                @if ($objectiveError)
                    <div class="error-text" style="margin-top: 10px;">{{ $objectiveError }}</div>
                @endif
            </div>

            <div class="dynamic-group">
                <div class="dynamic-group__header">
                    <span>Rangkuman Bab</span>
                    <button class="dynamic-add" data-add-chapter>Tambah bab</button>
                </div>
                <div class="dynamic-group__items" data-chapters data-next-index="{{ $chapterNextIndex }}">
                    @foreach ($chapterValues as $index => $chapter)
                        <div class="dynamic-item" data-chapter-row>
                            <div class="dynamic-item__row">
                                <input type="text" name="chapters[{{ $index }}][title]" value="{{ $chapter['title'] }}" placeholder="Judul bab" />
                                <textarea name="chapters[{{ $index }}][description]" placeholder="Ringkasan singkat bab">{{ $chapter['description'] }}</textarea>
                            </div>
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @php
                    $chapterError = $errors->first('chapters') ?: $errors->first('chapters.*.title') ?: $errors->first('chapters.*.description');
                @endphp
                @if ($chapterError)
                    <div class="error-text" style="margin-top: 10px;">{{ $chapterError }}</div>
                @endif
            </div>

            <label>
                <span>Upload File (PDF, PPT, DOC)</span>
                <div class="upload-field">
                    <input type="file" name="attachment" accept=".pdf,.ppt,.pptx,.doc,.docx" />
                    <div style="margin-top: 8px; font-size: 0.85rem;">Ukuran maksimal 10MB.</div>
                </div>
                @error('attachment')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <div class="form-actions">
                <a href="{{ route('tutor.materials.index') }}">Batal</a>
                <button type="submit">Simpan Draft</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const objectiveContainer = document.querySelector('[data-objectives]');
            const chapterContainer = document.querySelector('[data-chapters]');

            const templateObjective = () => {
                const wrapper = document.createElement('div');
                wrapper.className = 'dynamic-item';
                wrapper.dataset.objectiveRow = 'true';
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

            const templateChapter = (index) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'dynamic-item';
                wrapper.dataset.chapterRow = 'true';
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

            document.querySelectorAll('[data-remove-row]').forEach((button) => {
                button.addEventListener('click', () => {
                    const row = button.closest('.dynamic-item');
                    if (row && row.parentElement.children.length > 1) {
                        row.remove();
                    }
                });
            });

            const addObjectiveBtn = document.querySelector('[data-add-objective]');
            addObjectiveBtn?.addEventListener('click', (event) => {
                event.preventDefault();
                if (!objectiveContainer) return;

                const row = templateObjective();
                objectiveContainer.appendChild(row);
                row.querySelector('[data-remove-row]')?.addEventListener('click', () => {
                    if (row.parentElement.children.length > 1) {
                        row.remove();
                    }
                });
            });

            const addChapterBtn = document.querySelector('[data-add-chapter]');
            let nextChapterIndex = Number(chapterContainer?.dataset.nextIndex || chapterContainer?.children.length || 0);

            addChapterBtn?.addEventListener('click', (event) => {
                event.preventDefault();
                if (!chapterContainer) return;

                const row = templateChapter(nextChapterIndex++);
                chapterContainer.appendChild(row);
                row.querySelector('[data-remove-row]')?.addEventListener('click', () => {
                    if (row.parentElement.children.length > 1) {
                        row.remove();
                    }
                });
                if (chapterContainer) {
                    chapterContainer.dataset.nextIndex = String(nextChapterIndex);
                }
            });

            // AJAX Subject Dropdown
            const packageSelect = document.querySelector('select[name="package_id"]');
            const subjectSelect = document.getElementById('subject-select');

            if (packageSelect && subjectSelect) {
                packageSelect.addEventListener('change', function() {
                    const packageId = this.value;
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
                                    option.textContent = subject.name + ' (' + subject.level + ')';
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
                });

                // Trigger change if package is already selected (e.g. validation error)
                if (packageSelect.value) {
                    packageSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
@endpush
