@extends('adminlte::page')

@section('title', 'Tambah Pengiriman')

@section('content_header')
    <h1>Tambah Pengiriman</h1>
@stop

@section('content')
    <form action="{{ route('pengiriman-penjualan.store') }}" method="POST">
        @csrf

        {{-- Nomor Surat Jalan otomatis, readonly --}}
        <x-adminlte-input name="nomor_surat_jalan" label="Nomor Surat Jalan"
            value="{{ old('nomor_surat_jalan', $nomorSuratJalan ?? '') }}" readonly />

        {{-- Tanggal --}}
        <x-adminlte-input name="tanggal" label="Tanggal" type="date" value="{{ old('tanggal') }}" required />

        {{-- Pilih Sales Order --}}
        <x-adminlte-select name="id_so" label="Sales Order" required>
            <option value="">-- Pilih --</option>
            @foreach ($salesOrder as $so)
                <option value="{{ $so->id }}" {{ old('id_so') == $so->id ? 'selected' : '' }}>{{ $so->nomor_so }}
                </option>
            @endforeach
        </x-adminlte-select>

        <x-adminlte-button type="submit" label="Simpan" theme="primary" />
        <a href="{{ route('pengiriman-penjualan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@stop
