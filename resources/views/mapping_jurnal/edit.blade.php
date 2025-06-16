@extends('adminlte::page')

@section('title', 'Edit Mapping Jurnal')

@section('content')
    <div class="container-fluid">
        <h1>Edit Mapping Jurnal</h1>

        <form action="{{ route('mapping_jurnal.update', $mapping->id) }}" method="POST">
            @csrf
            @method('PUT')

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
                            <option value="{{ $modul }}"
                                {{ old('modul', $mapping->modul) == $modul ? 'selected' : '' }}>
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
                            <option value="{{ $event }}"
                                {{ old('event', $mapping->event) == $event ? 'selected' : '' }}>
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
                                {{ old('kode_akun_debit', $mapping->kode_akun_debit) == $coa->kode_akun ? 'selected' : '' }}>
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
                                {{ old('kode_akun_kredit', $mapping->kode_akun_kredit) == $coa->kode_akun ? 'selected' : '' }}>
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
                <textarea name="keterangan" class="form-control" style="resize: vertical;" rows="3">{{ old('keterangan', $mapping->keterangan) }}</textarea>
                @error('keterangan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label><i class="fas fa-stream"></i> Arus Kas Kelompok</label>
                    <select name="arus_kas_kelompok" class="form-control">
                        <option value="">-- Pilih Kelompok --</option>
                        @php
                            $kelompokList = ['operasi', 'investasi', 'pendanaan'];
                        @endphp
                        @foreach ($kelompokList as $item)
                            <option value="{{ $item }}"
                                {{ old('arus_kas_kelompok', $mapping->arus_kas_kelompok) == $item ? 'selected' : '' }}>
                                {{ ucfirst($item) }}
                            </option>
                        @endforeach
                    </select>
                    @error('arus_kas_kelompok')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label><i class="fas fa-random"></i> Arus Kas Jenis</label>
                    <select name="arus_kas_jenis" class="form-control">
                        <option value="">-- Pilih Jenis --</option>
                        @php
                            $jenisList = ['masuk', 'keluar'];
                        @endphp
                        @foreach ($jenisList as $item)
                            <option value="{{ $item }}"
                                {{ old('arus_kas_jenis', $mapping->arus_kas_jenis) == $item ? 'selected' : '' }}>
                                {{ ucfirst($item) }}
                            </option>
                        @endforeach
                    </select>
                    @error('arus_kas_jenis')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label><i class="fas fa-align-left"></i> Keterangan Arus Kas</label>
                    <input type="text" name="arus_kas_keterangan" class="form-control"
                        value="{{ old('arus_kas_keterangan', $mapping->arus_kas_keterangan) }}">
                    @error('arus_kas_keterangan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('mapping_jurnal.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </form>
    </div>
@endsection
