@extends('admin.layout')

@section('title', 'Dokumentasi Foto Les - MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --bg-surface: #ffffff;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius: 12px;
        }

        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-surface);
            padding: 24px 32px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
        }

        .header-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 4px 0;
        }

        .header-title p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #115e59;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        }

        .docs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        .doc-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s;
        }

        .doc-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .doc-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .doc-content {
            padding: 16px;
        }

        .doc-date {
            font-size: 0.85rem;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .doc-description {
            color: var(--text-main);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .doc-actions {
            display: flex;
            gap: 8px;
        }

        .btn-edit,
        .btn-delete {
            flex: 1;
            padding: 8px;
            border-radius: 6px;
            border: none;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-edit {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-edit:hover {
            background: #fde047;
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete:hover {
            background: #fca5a5;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .btn-close {
            background: #f1f5f9;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-close:hover {
            background: #e2e8f0;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .form-group input[type="date"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .file-upload {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-upload:hover {
            border-color: var(--primary);
            background: rgba(15, 118, 110, 0.05);
        }

        .file-upload input {
            display: none;
        }

        .preview-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            margin-top: 16px;
        }

        .modal-footer {
            padding: 16px 32px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-cancel,
        .btn-submit {
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: var(--text-main);
        }

        .btn-cancel:hover {
            background: #e2e8f0;
        }

        .btn-submit {
            background: var(--primary);
            color: white;
        }

        .btn-submit:hover {
            background: #115e59;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
    </style>
@endpush

@section('content')
    <div class="page-container">
        <div class="page-header">
            <div class="header-title">
                <h2>Dokumentasi Foto Les</h2>
                <p>Kelola foto kegiatan belajar mengajar yang ditampilkan di landing page</p>
            </div>
            <button class="btn-add" onclick="openAddModal()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Dokumentasi
            </button>
        </div>

        <div class="docs-grid">
            @forelse($documentations as $doc)
                <div class="doc-card">
                    <img src="{{ $doc['photo_url'] }}" alt="Dokumentasi" class="doc-image">
                    <div class="doc-content">
                        <div class="doc-date">📅 {{ $doc['activity_date_formatted'] }}</div>
                        <div class="doc-description">{{ $doc['description'] }}</div>
                        <div class="doc-actions">
                            <button class="btn-edit" onclick='openEditModal(@json($doc))'>Edit</button>
                            <form action="{{ route('admin.documentations.destroy', $doc['id']) }}" method="POST"
                                style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="confirmDelete(this.form)">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">
                    <svg style="width: 64px; height: 64px; margin-bottom: 16px; color: #cbd5e1;" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p>Belum ada dokumentasi foto</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div id="addModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Dokumentasi</h2>
                <button class="btn-close" onclick="closeAddModal()">✕</button>
            </div>
            <form action="{{ route('admin.documentations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Kegiatan *</label>
                        <input type="date" name="activity_date" required>
                    </div>
                    <div class="form-group">
                        <label>Kesan Singkat *</label>
                        <textarea name="description" required
                            placeholder="Ceritakan kesan selama kegiatan belajar mengajar..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Foto Kegiatan *</label>
                        <div class="file-upload" onclick="document.getElementById('addPhoto').click()">
                            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="margin: 0 auto 12px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p>Klik untuk upload foto</p>
                            <small style="color: var(--text-muted);">JPG, PNG (Max 5MB)</small>
                            <input type="file" id="addPhoto" name="photo" accept="image/*" required
                                onchange="previewAddImage(this)">
                            <img id="addPreview" class="preview-image" style="display: none;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan Dokumentasi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Dokumentasi</h2>
                <button class="btn-close" onclick="closeEditModal()">✕</button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Kegiatan *</label>
                        <input type="date" id="editDate" name="activity_date" required>
                    </div>
                    <div class="form-group">
                        <label>Kesan Singkat *</label>
                        <textarea id="editDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Foto Kegiatan (Opsional - Kosongkan jika tidak ingin mengubah)</label>
                        <img id="editCurrentPhoto" class="preview-image" style="margin-bottom: 16px;">
                        <div class="file-upload" onclick="document.getElementById('editPhoto').click()">
                            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="margin: 0 auto 12px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p>Klik untuk upload foto baru</p>
                            <input type="file" id="editPhoto" name="photo" accept="image/*"
                                onchange="previewEditImage(this)">
                            <img id="editPreview" class="preview-image" style="display: none;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-submit">Perbarui Dokumentasi</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function openAddModal() {
                document.getElementById('addModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeAddModal() {
                document.getElementById('addModal').classList.remove('active');
                document.body.style.overflow = 'auto';
                document.getElementById('addPreview').style.display = 'none';
            }

            function openEditModal(doc) {
                document.getElementById('editDate').value = doc.activity_date;
                document.getElementById('editDescription').value = doc.description;
                document.getElementById('editCurrentPhoto').src = doc.photo_url;
                document.getElementById('editForm').action = `/admin/documentations/${doc.id}`;
                document.getElementById('editModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.remove('active');
                document.body.style.overflow = 'auto';
                document.getElementById('editPreview').style.display = 'none';
            }

            function previewAddImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById('addPreview');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function previewEditImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById('editPreview');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function confirmDelete(form) {
                Swal.fire({
                    title: 'Hapus Dokumentasi?',
                    text: 'Foto dan data akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            @if(session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('status') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        </script>
    @endpush
@endsection