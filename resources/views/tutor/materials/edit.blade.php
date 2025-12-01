@extends('tutor.layout')

@section('title', 'Edit Folder - MayClass')

@push('styles')
    <style>
        /* Form Styles */
        .form-card {
            background: #fff;
            border-radius: 16px;
            max-width: 900px;
            margin: 40px auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            padding: 24px 32px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            cursor: pointer;
        }

        .close-btn:hover {
            color: #ef4444;
        }

        .form-body {
            padding: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.95rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #3fa67e;
            box-shadow: 0 0 0 3px rgba(63, 166, 126, 0.1);
        }

        textarea.form-control {
            min-height: 80px;
            resize: vertical;
        }

        /* Dynamic Groups */
        .dynamic-group {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            background: #f8fafc;
            margin-bottom: 20px;
        }

        .dynamic-group__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
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

        .dynamic-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
        }

        .dynamic-item__row {
            display: grid;
            gap: 12px;
        }

        .dynamic-item__actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 8px;
        }

        .dynamic-item__remove {
            border: none;
            background: transparent;
            color: #ef4444;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
        }

        /* Package Selector */
        .package-selector {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px;
            background: white;
            max-height: 250px;
            overflow-y: auto;
        }

        .package-checkbox-item {
            margin-bottom: 8px;
        }

        .package-checkbox {
            display: none;
        }

        .package-checkbox-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .package-checkbox:checked + .package-checkbox-label {
            border-color: #3fa67e;
            background: rgba(63, 166, 126, 0.1);
        }

        .package-checkbox:checked + .package-checkbox-label::before {
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

        .package-name {
            font-weight: 600;
            color: #1e293b;
        }

        .package-level {
            font-size: 0.85rem;
            color: #64748b;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 6px;
            margin-right: 28px;
        }

        /* Form Actions */
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
        }

        .btn-cancel:hover {
            background: #f1f5f9;
        }

        .btn-save {
            background: #3fa67e;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(63, 166, 126, 0.3);
        }

        .btn-save:hover {
            background: #2f8a67;
        }

        .error-text {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="form-card">
        <div class="form-header">
            <h1>Edit Folder</h1>
            <a href="{{ route('tutor.materials.index') }}" class="close-btn">&times;</a>
        </div>

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

            <form method="POST" action="{{ route('tutor.materials.update', $material) }}" id="material-form">
                @csrf
                @method('PUT')

                {{-- Hidden Level --}}
                <input type="hidden" name="level" id="hiddenLevel" value="{{ old('level', $material->level) }}">

                {{-- Package Selection --}}
                <div class="form-group">
                    <label class="form-label">Pilih Paket Belajar (bisa lebih dari 1)</label>
                    <div class="package-selector">
                        @forelse ($packages as $package)
                            <div class="package-checkbox-item">
                                <input type="checkbox" name="package_ids[]" id="package_{{ $package->id }}"
                                    value="{{ $package->id }}" data-level="{{ $package->level }}" 
                                    class="package-checkbox"
                                    onchange="handlePackageSelection()"
                                    {{ in_array($package->id, $selectedPackageIds) ? 'checked' : '' }}>
                                <label for="package_{{ $package->id }}" class="package-checkbox-label">
                                    <span class="package-name">{{ $package->detail_title }}</span>
                                    <span class="package-level">{{ $package->level }}</span>
                                </label>
                            </div>
                        @empty
                            <div style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada paket tersedia</div>
                        @endforelse
                    </div>
                    @error('package_ids') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                {{-- Subject Selection --}}
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" id="subjectSelect" class="form-control" required>
                        <option value="">Memuat...</option>
                    </select>
                    @error('subject_id') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                {{-- Folder Name --}}
                <div class="form-group">
                    <label class="form-label">Nama Folder</label>
                    <input type="text" name="title" class="form-control" 
                        value="{{ old('title', $material->title) }}" 
                        placeholder="Contoh: Aljabar Dasar" required>
                    @error('title') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                {{-- Folder Description --}}
                <div class="form-group">
                    <label class="form-label">Deskripsi Folder</label>
                    <textarea name="summary" class="form-control" 
                        placeholder="Deskripsi singkat folder..." required>{{ old('summary', $material->summary) }}</textarea>
                    @error('summary') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                {{-- Material Items --}}
                <div class="dynamic-group">
                    <div class="dynamic-group__header">
                        <span>Materi dalam Folder</span>
                        <button type="button" class="dynamic-add" onclick="addMaterialItem()">+ Tambah Materi</button>
                    </div>
                    <div class="dynamic-group__items" id="materialItemsList">
                        @forelse(old('material_items', $material->materialItems->toArray()) as $index => $item)
                            <div class="dynamic-item">
                                <div class="dynamic-item__row">
                                    <div class="form-group" style="margin-bottom: 12px;">
                                        <label class="form-label" style="font-size: 0.85rem;">Nama Materi</label>
                                        <input type="text" name="material_items[{{ $index }}][name]" class="form-control" 
                                            value="{{ is_array($item) ? ($item['name'] ?? '') : $item->name }}"
                                            placeholder="Contoh: Pengenalan Variabel" required />
                                    </div>
                                    <div class="form-group" style="margin-bottom: 12px;">
                                        <label class="form-label" style="font-size: 0.85rem;">Apa yang Dipelajari</label>
                                        <textarea name="material_items[{{ $index }}][description]" class="form-control" rows="2"
                                            placeholder="Deskripsi materi..." required>{{ is_array($item) ? ($item['description'] ?? '') : $item->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 0.85rem;">Link Materi</label>
                                        <input type="url" name="material_items[{{ $index }}][link]" class="form-control" 
                                            value="{{ is_array($item) ? ($item['link'] ?? '') : $item->link }}"
                                            placeholder="https://drive.google.com/..." required />
                                    </div>
                                </div>
                                <div class="dynamic-item__actions">
                                    <button type="button" class="dynamic-item__remove" onclick="removeMaterialItem(this)">Hapus Materi</button>
                                </div>
                            </div>
                        @empty
                            <div class="dynamic-item">
                                <div class="dynamic-item__row">
                                    <div class="form-group" style="margin-bottom: 12px;">
                                        <label class="form-label" style="font-size: 0.85rem;">Nama Materi</label>
                                        <input type="text" name="material_items[0][name]" class="form-control" 
                                            placeholder="Contoh: Pengenalan Variabel" required />
                                    </div>
                                    <div class="form-group" style="margin-bottom: 12px;">
                                        <label class="form-label" style="font-size: 0.85rem;">Apa yang Dipelajari</label>
                                        <textarea name="material_items[0][description]" class="form-control" rows="2"
                                            placeholder="Deskripsi materi..." required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 0.85rem;">Link Materi</label>
                                        <input type="url" name="material_items[0][link]" class="form-control" 
                                            placeholder="https://drive.google.com/..." required />
                                    </div>
                                </div>
                                <div class="dynamic-item__actions">
                                    <button type="button" class="dynamic-item__remove" onclick="removeMaterialItem(this)">Hapus Materi</button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @error('material_items') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('tutor.materials.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let materialItemIndex = {{ count(old('material_items', $material->materialItems)) }};
        const currentSubjectId = "{{ old('subject_id', $material->subject_id) }}";

        // Add Material Item
        window.addMaterialItem = function() {
            const list = document.getElementById('materialItemsList');
            if (!list) return;
            
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="dynamic-item__row">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" style="font-size: 0.85rem;">Nama Materi</label>
                        <input type="text" name="material_items[${materialItemIndex}][name]" class="form-control" 
                            placeholder="Contoh: Pengenalan Variabel" required />
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" style="font-size: 0.85rem;">Apa yang Dipelajari</label>
                        <textarea name="material_items[${materialItemIndex}][description]" class="form-control" rows="2"
                            placeholder="Deskripsi materi..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size: 0.85rem;">Link Materi</label>
                        <input type="url" name="material_items[${materialItemIndex}][link]" class="form-control" 
                            placeholder="https://drive.google.com/..." required />
                    </div>
                </div>
                <div class="dynamic-item__actions">
                    <button type="button" class="dynamic-item__remove" onclick="removeMaterialItem(this)">Hapus Materi</button>
                </div>
            `;
            list.appendChild(div);
            materialItemIndex++;
        };

        // Remove Material Item
        window.removeMaterialItem = function(button) {
            const list = document.getElementById('materialItemsList');
            if (!list) return;
            
            if (list.children.length > 1) {
                button.closest('.dynamic-item').remove();
            } else {
                alert('Minimal harus ada satu materi dalam folder!');
            }
        };

        // Handle Package Selection
        window.handlePackageSelection = function() {
            const checkboxes = document.querySelectorAll('.package-checkbox:checked');
            const levelInput = document.getElementById('hiddenLevel');
            const subjectSelect = document.getElementById('subjectSelect');
            
            if (checkboxes.length > 0) {
                const firstPackage = checkboxes[0];
                const packageId = firstPackage.value;
                const packageLevel = firstPackage.dataset.level;
                
                if (levelInput) {
                    levelInput.value = packageLevel;
                }
                
                loadSubjects(packageId, currentSubjectId);
            } else {
                subjectSelect.innerHTML = '<option value="">-- Pilih Paket Dulu --</option>';
                subjectSelect.disabled = true;
                
                if (levelInput) {
                    levelInput.value = '';
                }
            }
        };

        // Load Subjects
        function loadSubjects(packageId, selectedId = null) {
            const subjectSelect = document.getElementById('subjectSelect');
            const levelInput = document.getElementById('hiddenLevel');
            
            subjectSelect.innerHTML = '<option value="">Memuat...</option>';
            subjectSelect.disabled = true;

            fetch(`/tutor/packages/${packageId}/subjects`)
                .then(response => response.json())
                .then(data => {
                    subjectSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name + ' (' + subject.level + ')';
                        option.setAttribute('data-level', subject.level || '');
                        
                        if (selectedId && String(subject.id) === String(selectedId)) {
                            option.selected = true;
                            if (levelInput) levelInput.value = subject.level || '';
                        }
                        
                        subjectSelect.appendChild(option);
                    });
                    subjectSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    subjectSelect.innerHTML = '<option value="">Gagal memuat</option>';
                });
        }

        // Subject change listener
        document.addEventListener('DOMContentLoaded', function() {
            const subjectSelect = document.getElementById('subjectSelect');
            const levelInput = document.getElementById('hiddenLevel');
            
            if (subjectSelect) {
                subjectSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const level = selectedOption.getAttribute('data-level') || '';
                    if (levelInput) {
                        levelInput.value = level;
                    }
                });
            }
            
            // Initial load
            const firstCheckedPackage = document.querySelector('.package-checkbox:checked');
            if (firstCheckedPackage) {
                loadSubjects(firstCheckedPackage.value, currentSubjectId);
            }
        });
    </script>
@endpush