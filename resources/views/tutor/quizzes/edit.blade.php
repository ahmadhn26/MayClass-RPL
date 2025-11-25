@extends('tutor.layout')

@section('title', 'Edit Quiz - MayClass')

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
        input[type="url"],
        textarea {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #d9e0ea;
            border-radius: 16px;
            font-family: inherit;
            font-size: 1rem;
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
            background: rgba(95, 106, 248, 0.12);
            color: var(--primary-dark);
            font-weight: 600;
            cursor: pointer;
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
        $levelValues = collect(old('levels', $quiz->levels->pluck('label')->all()))
            ->map(fn($value) => is_string($value) ? $value : '');
        if ($levelValues->isEmpty()) {
            $levelValues = collect(['']);
        }

        $takeawayValues = collect(old('takeaways', $quiz->takeaways->pluck('description')->all()))
            ->map(fn($value) => is_string($value) ? $value : '');
        if ($takeawayValues->isEmpty()) {
            $takeawayValues = collect(['']);
        }
    @endphp
    <div class="form-card">
        <h1>Edit Quiz</h1>
        <p>Perbarui informasi quiz.</p>

        <form method="POST" action="{{ route('tutor.quizzes.update', $quiz) }}" class="form-grid">
            @csrf
            @method('PUT')
            <label>
                <span>Paket Belajar</span>
                <select name="package_id" required style="width: 100%; padding: 14px 18px; border: 1px solid #d9e0ea; border-radius: 16px; font-family: inherit; font-size: 1rem;">
                    <option value="">Pilih paket yang tersedia</option>
                    @forelse ($packages as $package)
                        <option value="{{ $package->id }}" @selected(old('package_id', $quiz->package_id) == $package->id)>
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
                <span>Judul Quiz</span>
                <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required />
                @error('title')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Mata Pelajaran</span>
                <select name="subject_id" id="subject-select" required style="width: 100%; padding: 14px 18px; border: 1px solid #d9e0ea; border-radius: 16px; font-family: inherit; font-size: 1rem;">
                    <option value="">Memuat...</option>
                </select>
                @error('subject_id')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Kelas</span>
                <input type="text" name="class_level" value="{{ old('class_level', $quiz->class_level) }}" required />
                @error('class_level')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Durasi Pengerjaan</span>
                <input type="text" name="duration_label" value="{{ old('duration_label', $quiz->duration_label) }}" required />
                @error('duration_label')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Jumlah Soal</span>
                <input type="number" min="1" max="200" name="question_count" value="{{ old('question_count', $quiz->question_count) }}" required />
                @error('question_count')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <label>
                <span>Deskripsi</span>
                <textarea name="summary" required>{{ old('summary', $quiz->summary) }}</textarea>
                @error('summary')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <div class="dynamic-group">
                <div class="dynamic-group__header">
                    <span>Level atau Kompetensi</span>
                    <button class="dynamic-add" data-add-level>Tambah level</button>
                </div>
                <div class="dynamic-group__items" data-levels>
                    @foreach ($levelValues as $value)
                        <div class="dynamic-item" data-level-row>
                            <input type="text" name="levels[]" value="{{ $value }}" placeholder="Contoh: Level Dasar" />
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @php
                    $levelError = $errors->first('levels') ?: $errors->first('levels.*');
                @endphp
                @if ($levelError)
                    <div class="error-text" style="margin-top: 10px;">{{ $levelError }}</div>
                @endif
            </div>

            <div class="dynamic-group">
                <div class="dynamic-group__header">
                    <span>Highlight Pembelajaran</span>
                    <button class="dynamic-add" data-add-takeaway>Tambah highlight</button>
                </div>
                <div class="dynamic-group__items" data-takeaways>
                    @foreach ($takeawayValues as $value)
                        <div class="dynamic-item" data-takeaway-row>
                            <input type="text" name="takeaways[]" value="{{ $value }}" placeholder="Contoh: Evaluasi persamaan linear" />
                            <div class="dynamic-item__actions">
                                <button type="button" class="dynamic-item__remove" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @php
                    $takeawayError = $errors->first('takeaways') ?: $errors->first('takeaways.*');
                @endphp
                @if ($takeawayError)
                    <div class="error-text" style="margin-top: 10px;">{{ $takeawayError }}</div>
                @endif
            </div>

            <label>
                <span>Link Quiz</span>
                <input type="url" name="link_url" value="{{ old('link_url', $quiz->link) }}" required />
                @error('link_url')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </label>

            <div class="form-actions">
                <a href="{{ route('tutor.quizzes.index') }}">Batal</a>
                <button type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const levelContainer = document.querySelector('[data-levels]');
            const takeawayContainer = document.querySelector('[data-takeaways]');

            const templateRow = (name, placeholder) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'dynamic-item';
                wrapper.innerHTML = `
                    <input type="text" name="${name}" placeholder="${placeholder}" />
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

            const bindRemoval = (row) => {
                row.querySelector('[data-remove-row]')?.addEventListener('click', () => {
                    if (row.parentElement.children.length > 1) {
                        row.remove();
                    }
                });
            };

            const addLevelBtn = document.querySelector('[data-add-level]');
            addLevelBtn?.addEventListener('click', (event) => {
                event.preventDefault();
                if (!levelContainer) return;

                const row = templateRow('levels[]', 'Contoh: Level Lanjutan');
                levelContainer.appendChild(row);
                bindRemoval(row);
            });

            const addTakeawayBtn = document.querySelector('[data-add-takeaway]');
            addTakeawayBtn?.addEventListener('click', (event) => {
                event.preventDefault();
                if (!takeawayContainer) return;

                const row = templateRow('takeaways[]', 'Contoh: Evaluasi persamaan linear');
                takeawayContainer.appendChild(row);
                bindRemoval(row);
            });

            // AJAX Subject Dropdown
            const packageSelect = document.querySelector('select[name="package_id"]');
            const subjectSelect = document.getElementById('subject-select');
            const currentSubjectId = "{{ old('subject_id', $quiz->subject_id) }}";

            if (packageSelect && subjectSelect) {
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
                                    option.textContent = subject.name + ' (' + subject.level + ')';
                                    if (selectedId && String(subject.id) === String(selectedId)) {
                                        option.selected = true;
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

                packageSelect.addEventListener('change', function() {
                    loadSubjects(this.value);
                });

                if (packageSelect.value) {
                    loadSubjects(packageSelect.value, currentSubjectId);
                }
            }
        });
    </script>
@endpush
