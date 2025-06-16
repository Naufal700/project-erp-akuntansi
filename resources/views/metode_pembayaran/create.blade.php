@extends('adminlte::page')

@section('title', isset($metode) ? 'Edit Cara Bayar' : 'Tambah Cara Bayar')

@section('content_header')
    <h1>{{ isset($metode) ? 'Edit' : 'Tambah' }} Cara Bayar</h1>
@stop

@section('content')
    <form action="{{ isset($metode) ? route('metode-pembayaran.update', $metode->id) : route('metode-pembayaran.store') }}"
        method="POST">
        @csrf
        @if (isset($metode))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label>Nama Cara Bayar</label>
            <select name="nama" class="form-control" required>
                <option value="">Pilih Cara Bayar</option>
                <option value="Tunai" {{ old('nama', $metode->nama ?? '') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="EDC" {{ old('nama', $metode->nama ?? '') == 'EDC' ? 'selected' : '' }}>EDC</option>
                <option value="Transfer" {{ old('nama', $metode->nama ?? '') == 'Transfer' ? 'selected' : '' }}>Transfer
                </option>
                <option value="QRIS" {{ old('nama', $metode->nama ?? '') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                <!-- Tambah cara bayar lain di sini -->
            </select>
        </div>

        <div class="mb-3">
            <label>Tipe Cara Bayar</label>
            <select name="tipe" class="form-control" required>
                <option value="">Pilih Tipe</option>
                <option value="kas" {{ old('tipe', $metode->tipe ?? '') == 'kas' ? 'selected' : '' }}>Kas</option>
                <option value="bank" {{ old('tipe', $metode->tipe ?? '') == 'bank' ? 'selected' : '' }}>Bank</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Akun COA</label>
            <select name="kode_akun" class="form-control" required>
                <option value="">Pilih Akun</option>
                @foreach ($akunKasBank as $akun)
                    <option value="{{ $akun->kode_akun }}"
                        {{ old('kode_akun', $metode->kode_akun ?? '') == $akun->kode_akun ? 'selected' : '' }}>
                        {{ $akun->nama_akun }} - {{ $akun->kode_akun }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ old('keterangan', $metode->keterangan ?? '') }}</textarea>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('metode-pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@stop
