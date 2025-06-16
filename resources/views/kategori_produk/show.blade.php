@extends('adminlte::page')

@section('title', 'Detail Kategori')

@section('content_header')
    <h1>Detail Kategori</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>{{ $kategori->nama_kategori }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Kode Kategori:</strong> {{ $kategori->kode_kategori }}</p>
            <p><strong>Deskripsi:</strong> {{ $kategori->deskripsi ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $kategori->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            <p><strong>Dibuat pada:</strong> {{ $kategori->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>
@stop
