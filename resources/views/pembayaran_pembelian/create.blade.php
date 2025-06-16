@extends('adminlte::page')

@section('title', 'Tambah Pembayaran Pembelian')

@section('content_header')
    <h1 class="font-weight-bold">Tambah Pembayaran Pembelian</h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('pembayaran-pembelian.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_kontrabon" class="form-label">Pilih Kontrabon</label>
                        <select name="id_kontrabon" id="id_kontrabon" class="form-control" required>
                            <option value="">-- Pilih Kontrabon --</option>
                            @foreach ($kontrabon as $item)
                                @php
                                    $totalBayar = $item->pembayaran->sum('jumlah') ?? 0;
                                    $sisa = $item->total - $totalBayar;
                                @endphp
                                <option value="{{ $item->id }}" data-total="{{ $item->total }}"
                                    data-sisa="{{ $sisa }}">
                                    {{ $item->nomor_kontrabon }} - Sisa Rp {{ number_format($sisa, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="sisa_tagihan" class="form-label">Sisa Tagihan</label>
                        <input type="text" id="sisa_tagihan" class="form-control bg-light" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="metode" class="form-label">Metode Pembayaran</label>
                        <select name="metode" class="form-control" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            @foreach ($metodePembayaran as $metode)
                                <option value="{{ $metode->nama }}">{{ $metode->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="jumlah" class="form-label">Jumlah Pembayaran</label>
                    <input type="number" step="0.01" name="jumlah" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('pembayaran-pembelian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@push('js')
    <script>
        document.getElementById('id_kontrabon').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const sisa = selected.getAttribute('data-sisa');

            if (sisa) {
                document.querySelector('input[name="jumlah"]').value = sisa;
                document.getElementById('sisa_tagihan').value = formatRupiah(sisa);
            } else {
                document.querySelector('input[name="jumlah"]').value = '';
                document.getElementById('sisa_tagihan').value = '';
            }
        });

        function formatRupiah(angka) {
            angka = parseFloat(angka);
            if (isNaN(angka)) return '';
            return 'Rp ' + angka.toLocaleString('id-ID', {
                minimumFractionDigits: 0
            });
        }
    </script>
@endpush
