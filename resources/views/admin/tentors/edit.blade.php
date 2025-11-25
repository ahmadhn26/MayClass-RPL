@extends('admin.layout')

@section('title', 'Edit Tentor - MayClass')

@push('styles')
    @include('admin.tentors.partials.form-styles')
@endpush

@section('content')
    <div class="tentor-form-wrapper">
        <div class="tentor-form-card">
            <h2>Perbarui Data Tentor</h2>
            <p>Semua perubahan akan langsung tercermin pada dashboard tentor.</p>
            @if (session('status'))
                <p class="helper-text" style="color:#0f766e; font-weight:600;">{{ session('status') }}</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.tentors.update', $tentor) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tentors.partials.form-fields', ['mode' => 'edit'])
        </form>

        <div class="danger-card">
            <h3>Hapus Tentor</h3>
            <p>Penghapusan bersifat permanen dan akan menghapus akses tentor ke dashboard.</p>
            <form method="POST" action="{{ route('admin.tentors.destroy', $tentor) }}" onsubmit="return confirm('Anda yakin ingin menghapus tentor ini? Tindakan tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus Tentor</button>
            </form>
        </div>
    </div>
@endsection
