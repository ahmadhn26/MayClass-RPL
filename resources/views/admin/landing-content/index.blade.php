@extends('admin.layout')

@section('title', 'Manajemen Content - MayClass')

@push('styles')
    <style>
        :root {
            --primary: #0f766e;
            --primary-light: #ccfbf1;
            --primary-dark: #115e59;
            --bg-surface: #ffffff;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        .content-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* --- HEADER (Matches Finance) --- */
        .page-header {
            background: var(--bg-surface);
            padding: 24px 32px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 4px 0;
        }

        .header-content p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* --- SECTIONS --- */
        .section-block {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::before {
            content: '';
            display: block;
            width: 4px;
            height: 16px;
            background: var(--primary);
            border-radius: 2px;
        }

        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-add:hover {
            background: var(--primary-dark);
        }

        /* --- CARDS GRID (Compact) --- */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .content-card {
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .content-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .card-img-wrapper {
            position: relative;
            height: 140px;
            overflow: hidden;
            background: var(--bg-body);
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-body {
            padding: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            line-height: 1.3;
        }

        .card-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: auto;
            padding-top: 8px;
        }

        .meta-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
        }

        .meta-info {
            display: flex;
            flex-direction: column;
        }

        .meta-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .meta-role {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .card-actions {
            padding: 8px 12px;
            border-top: 1px solid var(--border-color);
            background: #f8fafc;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            color: var(--text-muted);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .btn-icon:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-main);
        }

        .btn-icon.text-danger:hover {
            color: #ef4444;
            background: #fef2f2;
        }

        .empty-state {
            grid-column: 1 / -1;
            padding: 24px;
            text-align: center;
            background: var(--bg-body);
            border-radius: var(--radius);
            border: 1px dashed var(--border-color);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* --- GLASSMORPHISM MODAL --- */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 20px;
            padding: 24px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.95);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-backdrop.active .modal-glass {
            transform: scale(1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-muted);
            cursor: pointer;
            line-height: 1;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: #fff;
            font-family: inherit;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(15, 118, 110, 0.2);
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="content-container">

        {{-- Header --}}
        <div class="page-header">
            <div class="header-content">
                <h2>Manajemen Content</h2>
                <p>Kelola konten landing page MayClass secara dinamis dan real-time.</p>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Hero Section</h3>
                @if(($contents['hero'] ?? collect())->isEmpty())
                    <button onclick="openModal('create', 'hero')" class="btn-add">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                @endif
            </div>
            <div class="content-grid" style="grid-template-columns: 1fr;">
                @forelse($contents['hero'] ?? [] as $item)
                    <div class="content-card" style="flex-direction: row; align-items: center;">
                        <div class="card-body">
                            <h4 class="card-title">{{ $item->content['title'] ?? 'No Title' }}</h4>
                            <p class="card-desc" style="font-size: 1rem; color: var(--text-main);">
                                {{ $item->content['subtitle'] ?? '' }}</p>
                            <p class="card-desc" style="margin-top: 4px;">
                                {{ Str::limit($item->content['description'] ?? '', 150) }}</p>
                        </div>
                        <div class="card-actions"
                            style="border-top: none; background: transparent; border-left: 1px solid var(--border-color); flex-direction: column; justify-content: center;">
                            <button onclick="openModal('edit', 'hero', {{ $item }})" class="btn-icon">Edit</button>
                            <form action="{{ route('admin.landing-content.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus konten ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon text-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada konten Hero.</div>
                @endforelse
            </div>
        </div>

        {{-- Articles Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Artikel / Wawasan</h3>
                <button onclick="openModal('create', 'article')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid">
                @forelse($contents['article'] ?? [] as $item)
                    <div class="content-card">
                        @if($item->image)
                            <div class="card-img-wrapper">
                                <img src="{{ asset($item->image) }}" alt="Thumbnail" class="card-img">
                            </div>
                        @endif
                        <div class="card-body">
                            <h4 class="card-title">{{ $item->content['title'] ?? '' }}</h4>
                            <p class="card-desc">{{ Str::limit($item->content['description'] ?? '', 60) }}</p>
                            @if(!empty($item->content['link']))
                                <a href="{{ $item->content['link'] }}" target="_blank"
                                    style="font-size: 0.75rem; color: var(--primary); text-decoration: none; margin-top: auto;">
                                    Link: {{ Str::limit($item->content['link'], 30) }}
                                </a>
                            @endif
                        </div>
                        <div class="card-actions">
                            <button onclick="openModal('edit', 'article', {{ $item }})" class="btn-icon">Edit</button>
                            <form action="{{ route('admin.landing-content.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus konten ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon text-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada artikel.</div>
                @endforelse
            </div>
        </div>

        {{-- Features Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Keunggulan (Highlight)</h3>
                <button onclick="openModal('create', 'feature')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid">
                @forelse($contents['feature'] ?? [] as $item)
                    <div class="content-card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $item->content['title'] ?? '' }}</h4>
                            <p class="card-desc">{{ $item->content['description'] ?? '' }}</p>
                        </div>
                        <div class="card-actions">
                            <button onclick="openModal('edit', 'feature', {{ $item }})" class="btn-icon">Edit</button>
                            <form action="{{ route('admin.landing-content.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus konten ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon text-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada keunggulan.</div>
                @endforelse
            </div>
        </div>

        {{-- Testimonials Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Testimoni</h3>
                <button onclick="openModal('create', 'testimonial')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid">
                @forelse($contents['testimonial'] ?? [] as $item)
                    <div class="content-card">
                        <div class="card-body">
                            <div class="card-meta" style="margin-top: 0; margin-bottom: 8px; padding-top: 0;">
                                @if($item->image)
                                    <img src="{{ asset($item->image) }}" alt="Avatar" class="meta-avatar">
                                @else
                                    <div class="meta-avatar" style="background: #e2e8f0;"></div>
                                @endif
                                <div class="meta-info">
                                    <span class="meta-name">{{ $item->content['name'] ?? '' }}</span>
                                    <span class="meta-role">{{ $item->content['role'] ?? '' }}</span>
                                </div>
                            </div>
                            <p class="card-desc" style="font-style: italic;">
                                "{{ Str::limit($item->content['quote'] ?? '', 100) }}"</p>
                        </div>
                        <div class="card-actions">
                            <button onclick="openModal('edit', 'testimonial', {{ $item }})" class="btn-icon">Edit</button>
                            <form action="{{ route('admin.landing-content.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus konten ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon text-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada testimoni.</div>
                @endforelse
            </div>
        </div>

        {{-- Mentors Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">Mentor</h3>
                <button onclick="openModal('create', 'mentor')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid">
                @forelse($contents['mentor'] ?? [] as $item)
                    <div class="content-card">
                        @if($item->image)
                            <div class="card-img-wrapper">
                                <img src="{{ asset($item->image) }}" alt="Mentor" class="card-img">
                            </div>
                        @endif
                        <div class="card-body">
                            <h4 class="card-title">{{ $item->content['name'] ?? '' }}</h4>
                            <p class="card-desc" style="color: var(--primary); font-weight: 500;">
                                {{ $item->content['role'] ?? '' }}</p>
                            <p class="card-desc" style="margin-top: 4px;">"{{ Str::limit($item->content['quote'] ?? '', 80) }}"
                            </p>
                        </div>
                        <div class="card-actions">
                            <button onclick="openModal('edit', 'mentor', {{ $item }})" class="btn-icon">Edit</button>
                            <form action="{{ route('admin.landing-content.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus konten ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon text-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada mentor.</div>
                @endforelse
            </div>
        </div>

        {{-- FAQ Section --}}
        <div class="section-block">
            <div class="section-header">
                <h3 class="section-title">FAQ</h3>
                <button onclick="openModal('create', 'faq')" class="btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
            <div class="content-grid" style="grid-template-columns: 1fr;">
                @forelse($contents['faq'] ?? [] as $item)
                    <div class="content-card" style="flex-direction: row; align-items: center;">
                        <div class="card-body">
                            <h4 class="card-title" style="color: var(--primary); font-size: 0.9rem;">Q:
                                {{ $item->content['question'] ?? '' }}</h4>
                            <p class="card-desc" style="margin-top: 2px;">A: {{ $item->content['answer'] ?? '' }}</p>
                        </div>
                        <div class="card-actions"
                            style="border-top: none; background: transparent; border-left: 1px solid var(--border-color);">
                            <button onclick="openModal('edit', 'faq', {{ $item }})" class="btn-icon">Edit</button>
                            <form action="{{ route('admin.landing-content.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus konten ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon text-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada FAQ.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Glassmorphism Modal --}}
    <div id="contentModal" class="modal-backdrop">
        <div class="modal-glass">
            <div class="modal-header">
                <h2 id="modalTitle" class="modal-title">Tambah Konten</h2>
                <button type="button" onclick="closeModal()" class="btn-close">&times;</button>
            </div>
            <form id="contentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                <input type="hidden" name="section" id="sectionInput">

                <div id="dynamicFields">
                    <!-- Fields injected via JS -->
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal()" class="btn-cancel">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('contentModal');
        const form = document.getElementById('contentForm');
        const methodField = document.getElementById('methodField');
        const dynamicFields = document.getElementById('dynamicFields');
        const sectionInput = document.getElementById('sectionInput');
        const modalTitle = document.getElementById('modalTitle');

        function openModal(mode, section, data = null) {
            modal.classList.add('active');
            sectionInput.value = section;
            dynamicFields.innerHTML = '';

            if (mode === 'edit') {
                modalTitle.textContent = 'Edit Konten';
                form.action = `/admin/landing-content/${data.id}`;
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            } else {
                modalTitle.textContent = 'Tambah Konten';
                form.action = "{{ route('admin.landing-content.store') }}";
                methodField.innerHTML = '';
            }

            // Generate Fields based on Section
            let fields = [];

            if (section === 'hero') {
                fields = [
                    { name: 'content[title]', label: 'Judul Badge (Kecil)', type: 'text', value: data?.content?.title },
                    { name: 'content[subtitle]', label: 'Judul Utama (Besar)', type: 'text', value: data?.content?.subtitle },
                    { name: 'content[description]', label: 'Deskripsi', type: 'textarea', value: data?.content?.description },
                ];
            } else if (section === 'article') {
                fields = [
                    { name: 'content[title]', label: 'Judul Artikel', type: 'text', value: data?.content?.title },
                    { name: 'content[description]', label: 'Deskripsi Singkat', type: 'textarea', value: data?.content?.description },
                    { name: 'content[link]', label: 'Link Artikel (URL)', type: 'url', value: data?.content?.link, placeholder: 'https://...' },
                    { name: 'image', label: 'Gambar Thumbnail', type: 'file' },
                ];
            } else if (section === 'feature') {
                fields = [
                    { name: 'content[title]', label: 'Judul Keunggulan', type: 'text', value: data?.content?.title },
                    { name: 'content[description]', label: 'Deskripsi', type: 'textarea', value: data?.content?.description },
                ];
            } else if (section === 'testimonial') {
                fields = [
                    { name: 'content[name]', label: 'Nama Siswa', type: 'text', value: data?.content?.name },
                    { name: 'content[role]', label: 'Status/Role (ex: Lulus UI)', type: 'text', value: data?.content?.role },
                    { name: 'content[quote]', label: 'Testimoni', type: 'textarea', value: data?.content?.quote },
                    { name: 'image', label: 'Foto Profil', type: 'file' },
                ];
            } else if (section === 'mentor') {
                fields = [
                    { name: 'content[name]', label: 'Nama Mentor', type: 'text', value: data?.content?.name },
                    { name: 'content[role]', label: 'Bidang Studi', type: 'text', value: data?.content?.role },
                    { name: 'content[quote]', label: 'Kutipan', type: 'textarea', value: data?.content?.quote },
                    { name: 'content[meta_1]', label: 'Info Tambahan 1 (ex: 8+ Tahun Mengajar)', type: 'text', value: data?.content?.meta_1 },
                    { name: 'content[meta_2]', label: 'Info Tambahan 2 (ex: 700+ Siswa)', type: 'text', value: data?.content?.meta_2 },
                    { name: 'image', label: 'Foto Mentor', type: 'file' },
                ];
            } else if (section === 'faq') {
                fields = [
                    { name: 'content[question]', label: 'Pertanyaan', type: 'text', value: data?.content?.question },
                    { name: 'content[answer]', label: 'Jawaban', type: 'textarea', value: data?.content?.answer },
                ];
            }

            fields.forEach(field => {
                const wrapper = document.createElement('div');
                wrapper.className = 'form-group';

                const label = document.createElement('label');
                label.className = 'form-label';
                label.textContent = field.label;

                let input;
                if (field.type === 'textarea') {
                    input = document.createElement('textarea');
                    input.rows = 3; // Reduced rows for compactness
                } else {
                    input = document.createElement('input');
                    input.type = field.type;
                    if (field.placeholder) input.placeholder = field.placeholder;
                }

                input.name = field.name;
                input.className = 'form-control';
                if (field.value) input.value = field.value;

                wrapper.appendChild(label);
                wrapper.appendChild(input);
                dynamicFields.appendChild(wrapper);
            });
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        // Close on click outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    </script>
@endpush