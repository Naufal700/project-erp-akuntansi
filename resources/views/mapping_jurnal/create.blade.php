@extends('adminlte::page')

@section('title', 'Tambah Mapping Jurnal')

@section('content')
    <div class="container-fluid">
        <h1>Tambah Mapping Jurnal</h1>

        <form action="{{ route('mapping_jurnal.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-3">
                    <label><i class="fas fa-layer-group"></i> Modul</label>
                    <select name="modul" class="form-control">
                        <option value="">-- Pilih Modul --</option>
                        @php
                            $modulList = [
                                'Penjualan',
                                'Pembelian',
                                'Kas & Bank',
                                'Payroll',
                                'Persediaan',
                                'Fixed Asset',
                            ];
                        @endphp
                        @foreach ($modulList as $modul)
                            <option value="{{ $modul }}" {{ old('modul') == $modul ? 'selected' : '' }}>
                                {{ $modul }}
                            </option>
                        @endforeach
                    </select>
                    @error('modul')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label><i class="fas fa-calendar-alt"></i> Event</label>
                    <select name="event" class="form-control">
                        <option value="">-- Pilih Event --</option>
                        @php
                            $eventList = [
                                'Faktur Penjualan',
                                'Pembayaran Penjualan',
                                'Purchase Order',
                                'Faktur Pembelian',
                                'Pembayaran Pembelian',
                                'Kas Masuk',
                                'Kas Keluar',
                                'Gaji Bulanan',
                                'Penyusutan',
                                'Retur Penjualan',
                                'Retur Pembelian',
                            ];
                        @endphp
                        @foreach ($eventList as $event)
                            <option value="{{ $event }}" {{ old('event') == $event ? 'selected' : '' }}>
                                {{ $event }}
                            </option>
                        @endforeach
                    </select>
                    @error('event')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label><i class="fas fa-arrow-down"></i> Akun Debit</label>
                    <select name="kode_akun_debit" class="form-control">
                        <option value="">-- Pilih Akun Debit --</option>
                        @foreach ($coas as $coa)
                            <option value="{{ $coa->kode_akun }}"
                                {{ old('kode_akun_debit') == $coa->kode_akun ? 'selected' : '' }}>
                                {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                            </option>
                        @endforeach
                    </select>
                    @error('kode_akun_debit')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label><i class="fas fa-arrow-up"></i> Akun Kredit</label>
                    <select name="kode_akun_kredit" class="form-control">
                        <option value="">-- Pilih Akun Kredit --</option>
                        @foreach ($coas as $coa)
                            <option value="{{ $coa->kode_akun }}"
                                {{ old('kode_akun_kredit') == $coa->kode_akun ? 'selected' : '' }}>
                                {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                            </option>
                        @endforeach
                    </select>
                    @error('kode_akun_kredit')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group mt-3" style="max-width: 600px;">
                <label><i class="fas fa-sticky-note"></i> Keterangan</label>
                <textarea name="keterangan" class="form-control" style="resize: vertical;" rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label><i class="fas fa-sitemap"></i> Arus Kas Kelompok</label>
                    <select name="arus_kas_kelompok" class="form-control">
                        <option value="">-- Pilih Kelompok --</option>
                        @foreach (['operasi', 'investasi', 'pendanaan'] as $kelompok)
                            <option value="{{ $kelompok }}"
                                {{ old('arus_kas_kelompok') == $kelompok ? 'selected' : '' }}>
                                {{ ucfirst($kelompok) }}
                            </option>
                        @endforeach
                    </select>
                    @error('arus_kas_kelompok')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label><i class="fas fa-exchange-alt"></i> Arus Kas Jenis</label>
                    <select name="arus_kas_jenis" class="form-control">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach (['masuk', 'keluar'] as $jenis)
                            <option value="{{ $jenis }}" {{ old('arus_kas_jenis') == $jenis ? 'selected' : '' }}>
                                {{ ucfirst($jenis) }}
                            </option>
                        @endforeach
                    </select>
                    @error('arus_kas_jenis')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label><i class="fas fa-info-circle"></i> Arus Kas Keterangan</label>
                    <input type="text" name="arus_kas_keterangan" class="form-control"
                        value="{{ old('arus_kas_keterangan') }}" placeholder="Contoh: Pembayaran invoice penjualan">
                    @error('arus_kas_keterangan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus"></i> Simpan
            </button>
            <a href="{{ route('mapping_jurnal.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </form>
    </div>
@endsection
