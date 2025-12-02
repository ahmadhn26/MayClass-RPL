@extends('tutor.layout')

@section('title', 'Edit Quiz - MayClass')

@push('styles')
    <style>
        /* --- MODAL OVERLAY STYLE (MENGAMBANG) --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Background Gelap Transparan */
            backdrop-filter: blur(5px);
            /* Efek Blur */
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
            max-width: 800px;
            /* Lebar proporsional */
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
            flex-shrink: 0;
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

        /* Body Form (Scrollable) */
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
            /* 2 Kolom */
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
        input[type="url"],
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

        .error-text {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 4px;
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
            /* HIJAU */
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

        .btn-save:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        /* Responsive */
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
        // Load existing quiz items from database
        $quizItems = $quiz->quizItems ?? collect();

        // If validation error, use old input
        if (old('quiz_items')) {
            $quizItems = collect(old('quiz_items'))->map(function ($item) {
                return (object) $item;
            });
        }

        // Ensure at least one empty item for new entries
        if ($quizItems->isEmpty()) {
            $quizItems = collect([
                (object) [
                    'name' => '',
                    'description' => '',
                    'link' => ''
                ]
            ]);
        }
    @endphp

    {{-- WRAPPER OVERLAY --}}
    <div class="modal-overlay">

        {{-- CARD FORM --}}
        <div class="form-card">

            {{-- Header Form --}}
            <div class="form-header">
                <h1>Edit Quiz</h1>
                <a href="{{ route('tutor.quizzes.index') }}" class="close-btn">&times;</a>
            </div>

            {{-- Body Form --}}
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

                <form method="POST" action="{{ route('tutor.quizzes.update', $quiz) }}" id="quiz-form">
                    @csrf
                    @method('PUT')

                    {{--
                    DATA TERSEMBUNYI (HIDDEN)
                    Ini wajib ada karena di Controller kolom ini 'required'.
                    Kita kirim data lama ($quiz->...) agar tidak error saat disimpan.
                    --}}
                    <input type="hidden" name="class_level" value="{{ $quiz->class_level ?? '-' }}">
                    <input type="hidden" name="duration_label" value="{{ $quiz->duration_label ?? '-' }}">
                    <input type="hidden" name="question_count" value="{{ $quiz->question_count ?? 0 }}">

                    <div class="form-grid">
                        {{-- Paket Belajar --}}
                        <label>
                            <span>Paket Belajar</span>
                            <select name="package_id" id="package-select" required>
                                <option value="">Pilih paket yang tersedia</option>
                                @forelse ($packages as $package)
                                    <option value="{{ $package->id }}" @selected(old('package_id', $quiz->package_id) == $package->id)>
                                        {{ $package->detail_title ?? $package->title }}
                                    </option>
                                @empty
                                    <option value="" disabled>Belum ada paket yang tersedia</option>
                                @endforelse
                            </select>
                            @error('package_id') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        {{-- Judul Quiz --}}
                        <label>
                            <span>Judul Quiz</span>
                            <input type="text" name="title" value="{{ old('title', $quiz->title) }}"
                                placeholder="Contoh: Quiz Persamaan Linear" required />
                            @error('title') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        {{-- Mata Pelajaran --}}
                        <label class="span-full">
                            <span>Mata Pelajaran</span>
                            {{-- data-old digunakan untuk recovery saat validation error --}}
                            <select name="subject_id" id="subject-select"
                                data-old="{{ old('subject_id', $quiz->subject_id) }}" required>
                                <option value="">Pilih paket terlebih dahulu</option>
                            </select>
                            @error('subject_id') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        {{-- Deskripsi --}}
                        <label class="span-full">
                            <span>Deskripsi</span>
                            <textarea name="summary" placeholder="Tuliskan deskripsi singkat quiz..."
                                required>{{ old('summary', $quiz->summary) }}</textarea>
                            @error('summary') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        {{-- Quiz Items Section --}}
                        <div class="span-full" style="margin-top: 24px;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                <span style="font-weight: 600; font-size: 1rem; color: #1e293b;">Quiz Items</span>
                                <button type="button" onclick="addQuizItem()"
                                    style="background: #3fa67e; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 0.875rem; cursor: pointer; font-weight: 500;">
                                    + Tambah Quiz
                                </button>
                            </div>

                            <div id="quiz-items-container">
                                @foreach ($quizItems as $index => $item)
                                    <div class="quiz-item-row"
                                        style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 16px; border: 1px solid #e2e8f0;">
                                        <div
                                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                            <strong style="color: #475569; font-size: 0.9rem;">Quiz #{{ $index + 1 }}</strong>
                                            <button type="button" onclick="removeQuizItem(this)"
                                                style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; cursor: pointer;">
                                                Hapus
                                            </button>
                                        </div>

                                        <div style="display: grid; gap: 12px;">
                                            <label>
                                                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Nama
                                                    Quiz</span>
                                                <input type="text" name="quiz_items[{{ $index }}][name]"
                                                    value="{{ $item->name ?? '' }}" placeholder="Contoh: Quiz Persamaan Linear"
                                                    required style="margin-top: 4px;" />
                                            </label>

                                            <label>
                                                <span
                                                    style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Deskripsi</span>
                                                <textarea name="quiz_items[{{ $index }}][description]"
                                                    placeholder="Deskripsi quiz..." required
                                                    style="margin-top: 4px; min-height: 80px;">{{ $item->description ?? '' }}</textarea>
                                            </label>

                                            <label>
                                                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Link
                                                    Quiz</span>
                                                <input type="url" name="quiz_items[{{ $index }}][link]"
                                                    value="{{ $item->link ?? '' }}" placeholder="https://forms.google.com/..."
                                                    required style="margin-top: 4px;" />
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('quiz_items') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="form-actions">
                        <a href="{{ route('tutor.quizzes.index') }}" class="btn-cancel">
                            Batal
                        </a>
                        <button type="submit" class="btn-save" id="submit-btn">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const packageSelect = document.getElementById('package-select');
            const subjectSelect = document.getElementById('subject-select');
            const currentSubjectId = "{{ old('subject_id', $quiz->subject_id) }}";
            const form = document.getElementById('quiz-form');
            const submitBtn = document.getElementById('submit-btn');

            // AJAX Subject Dropdown (Logic Tidak Disentuh)
            if (packageSelect && subjectSelect) {
                const loadSubjects = (packageId, selectedId = null) => {
                    console.log('[EDIT DEBUG] loadSubjects called with packageId:', packageId, 'selectedId:', selectedId);
                    subjectSelect.innerHTML = '<option value="">Memuat...</option>';
                    subjectSelect.disabled = true;

                    if (packageId) {
                        // Use Laravel route helper for correct URL
                        const url = "{{ route('tutor.packages.subjects', ':id') }}".replace(':id', packageId);
                        console.log('[EDIT DEBUG] Fetching from URL:', url);

                        fetch(url)
                            .then(response => {
                                console.log('[EDIT DEBUG] Response:', response.status, response.statusText);
                                return response.json();
                            })
                            .then(data => {
                                console.log('[EDIT DEBUG] Data received:', data);
                                subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                                data.forEach(subject => {
                                    const option = document.createElement('option');
                                    option.value = subject.id;
                                    option.textContent = subject.name + ' (' + subject.level + ')';
                                    // Logic pilih otomatis
                                    if (selectedId && String(subject.id) === String(selectedId)) {
                                        option.selected = true;
                                    }
                                    subjectSelect.appendChild(option);
                                });
                                subjectSelect.disabled = false;
                                console.log('[EDIT DEBUG] Dropdown populated with', data.length, 'subjects');
                            })
                            .catch(error => {
                                console.error('[EDIT ERROR]', error);
                                subjectSelect.innerHTML = '<option value="">Gagal memuat mata pelajaran</option>';
                            });
                    } else {
                        console.log('[EDIT DEBUG] No packageId, showing placeholder');
                        subjectSelect.innerHTML = '<option value="">Pilih paket terlebih dahulu</option>';
                        subjectSelect.disabled = true;
                    }
                };

                packageSelect.addEventListener('change', function () {
                    loadSubjects(this.value);
                });

                // Initial load untuk Edit
                if (packageSelect.value) {
                    loadSubjects(packageSelect.value, currentSubjectId);
                }
            }

            // Disable button saat submit
            form?.addEventListener('submit', function () {
                // Update index sebelum submit
                updateQuizItemIndices();
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';
            });

            // --- Quiz Items Management ---
            window.addQuizItem = function () {
                const container = document.getElementById('quiz-items-container');
                const currentCount = container.children.length;
                const newIndex = currentCount;

                const newItem = document.createElement('div');
                newItem.className = 'quiz-item-row';
                newItem.style.cssText = 'background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 16px; border: 1px solid #e2e8f0;';
                newItem.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <strong style="color: #475569; font-size: 0.9rem;">Quiz #${newIndex + 1}</strong>
                            <button type="button" onclick="removeQuizItem(this)" 
                                style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; cursor: pointer;">
                                Hapus
                            </button>
                        </div>

                        <div style="display: grid; gap: 12px;">
                            <label>
                                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Nama Quiz</span>
                                <input type="text" name="quiz_items[${newIndex}][name]" 
                                    placeholder="Contoh: Quiz Persamaan Linear" 
                                    required 
                                    style="margin-top: 4px; width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 0.95rem; background-color: #fff;" />
                            </label>

                            <label>
                                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Deskripsi</span>
                                <textarea name="quiz_items[${newIndex}][description]" 
                                    placeholder="Deskripsi quiz..." 
                                    required 
                                    style="margin-top: 4px; min-height: 80px; width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 0.95rem; background-color: #fff; resize: vertical;"></textarea>
                            </label>

                            <label>
                                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Link Quiz</span>
                                <input type="url" name="quiz_items[${newIndex}][link]" 
                                    placeholder="https://forms.google.com/..." 
                                    required 
                                    style="margin-top: 4px; width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 0.95rem; background-color: #fff;" />
                            </label>
                        </div>
                    `;

                container.appendChild(newItem);
                updateQuizItemIndices();
            };

            window.removeQuizItem = function (button) {
                const container = document.getElementById('quiz-items-container');
                if (container.children.length <= 1) {
                    alert('Minimal harus ada 1 quiz item');
                    return;
                }

                const row = button.closest('.quiz-item-row');
                row.remove();
                updateQuizItemIndices();
            };

            function updateQuizItemIndices() {
                const container = document.getElementById('quiz-items-container');
                const items = container.querySelectorAll('.quiz-item-row');

                items.forEach((item, index) => {
                    // Update label
                    const label = item.querySelector('strong');
                    if (label) label.textContent = `Quiz #${index + 1}`;

                    // Update input names
                    const nameInput = item.querySelector('input[name*="[name]"]');
                    const descInput = item.querySelector('textarea[name*="[description]"]');
                    const linkInput = item.querySelector('input[name*="[link]"]');

                    if (nameInput) nameInput.name = `quiz_items[${index}][name]`;
                    if (descInput) descInput.name = `quiz_items[${index}][description]`;
                    if (linkInput) linkInput.name = `quiz_items[${index}][link]`;
                });
            }
        });
    </script>
@endpush