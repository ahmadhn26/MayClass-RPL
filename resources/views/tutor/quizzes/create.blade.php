@extends('tutor.layout')

@section('title', 'Tambah Quiz Baru - MayClass')

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
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
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
        .close-btn:hover { color: #ef4444; }

        .form-body {
            padding: 32px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        
        .form-body::-webkit-scrollbar { width: 6px; }
        .form-body::-webkit-scrollbar-track { background: transparent; }
        .form-body::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }

        /* --- GRID SYSTEM --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .span-full { grid-column: 1 / -1; }

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

        input:focus, textarea:focus, select:focus {
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-card { height: 100vh; max-height: 100vh; border-radius: 0; }
            .form-header { border-radius: 0; }
        }
    </style>
@endpush

@section('content')
    {{-- WRAPPER OVERLAY --}}
    <div class="modal-overlay">
        <div class="form-card">
            
            {{-- HEADER FORM --}}
            <div class="form-header">
                <h1>Tambah Quiz Baru</h1>
                <a href="{{ route('tutor.quizzes.index') }}" class="close-btn">&times;</a>
            </div>

            {{-- BODY FORM --}}
            <div class="form-body">
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

                <form method="POST" action="{{ route('tutor.quizzes.store') }}" id="quiz-form">
                    @csrf
                    
                    {{-- 
                        INPUT HIDDEN (DATA BOHONGAN)
                        Agar Controller tidak error, kita kirim data default
                    --}}
                    <input type="hidden" name="class_level" value="-">
                    <input type="hidden" name="duration_label" value="-">
                    <input type="hidden" name="question_count" value="1">

                    <div class="form-grid">
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
                            <span>Judul Quiz</span>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Quiz Persamaan Linear" required />
                            @error('title') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        <label class="span-full">
                            <span>Mata Pelajaran</span>
                            {{-- data-old digunakan untuk recovery saat validation error --}}
                            <select name="subject_id" id="subject-select" data-old="{{ old('subject_id') }}" required>
                                <option value="">Pilih paket terlebih dahulu</option>
                            </select>
                            @error('subject_id') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        <label class="span-full">
                            <span>Deskripsi</span>
                            <textarea name="summary" placeholder="Tuliskan deskripsi singkat quiz..." required>{{ old('summary') }}</textarea>
                            @error('summary') <div class="error-text">{{ $message }}</div> @enderror
                        </label>

                        <label class="span-full">
                            <span>Link Quiz</span>
                            <input type="url" name="link_url" value="{{ old('link_url') }}" placeholder="https://" required />
                            @error('link_url') <div class="error-text">{{ $message }}</div> @enderror
                        </label>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('tutor.quizzes.index') }}" class="btn-cancel">
                            Batal
                        </a>
                        <button type="submit" class="btn-save" id="submit-btn">
                            Simpan Quiz
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packageSelect = document.getElementById('package-select');
    const subjectSelect = document.getElementById('subject-select');
    const form = document.getElementById('quiz-form');
    const submitBtn = document.getElementById('submit-btn');

    // AJAX Subject Dropdown
    if (packageSelect && subjectSelect) {
        const loadSubjects = (packageId, selectedId = null) => {
            subjectSelect.innerHTML = '<option value="">Memuat...</option>';
            subjectSelect.disabled = true;

            if (packageId) {
                fetch(/tutor/packages/${packageId}/subjects)
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

        // Jika package sudah dipilih (setelah validation error), load subjects
        if (packageSelect.value) {
            const oldSubjectId = subjectSelect.getAttribute('data-old');
            loadSubjects(packageSelect.value, oldSubjectId);
        }
    }

    // Disable button saat submit
    form?.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';
    });
});
</script>
@endpush