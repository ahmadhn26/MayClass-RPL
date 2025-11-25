@extends('admin.layout')

@section('title', 'Tambah Tentor - MayClass')

@push('styles')
    @include('admin.tentors.partials.form-styles')
@endpush

@section('content')
    <div class="tentor-form-wrapper">
        <div class="tentor-form-card">
            <h2>Tambah Tentor Baru</h2>
            <p>Input lengkap profil tentor agar halaman tentor langsung siap ditampilkan.</p>
        </div>

        <form method="POST" action="{{ route('admin.tentors.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.tentors.partials.form-fields', ['mode' => 'create'])
        </form>
    </div>
@endsection
