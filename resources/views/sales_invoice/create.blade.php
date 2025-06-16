@extends('adminlte::page')

@section('title', 'Buat Faktur Penjualan')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Buat Faktur Penjualan</h5>
                    </div>

                    <div class="card-body px-4 py-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Terjadi kesalahan:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('sales-invoice.store') }}" method="POST" autocomplete="off">
                            @csrf

                            <div class="form-group">
                                <label for="id_so" class="font-weight-bold">Sales Order <span
                                        class="text-danger">*</span></label>
                                <select name="id_so" id="id_so" class="form-control select2" required>
                                    <option value="">-- Pilih Sales Order --</option>
                                    @foreach ($salesOrders as $so)
                                        <option value="{{ $so->id }}">
                                            {{ $so->nomor_so }} - {{ $so->customer->nama ?? 'Customer Tidak Diketahui' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tanggal" class="font-weight-bold">Tanggal Invoice <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control"
                                    value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="ppn" class="font-weight-bold">PPN</label>
                                <select name="ppn" id="ppn" class="form-control" required>
                                    <option value="0" {{ old('ppn') == '0' ? 'selected' : '' }}>Tidak</option>
                                    <option value="11" {{ old('ppn', 11) == '11' ? 'selected' : '' }}>Iya (11%)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="jatuh_tempo" class="font-weight-bold">Jatuh Tempo</label>
                                <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="form-control"
                                    value="{{ old('jatuh_tempo') }}">
                            </div>

                            <div class="form-group text-right mt-4">
                                <a href="{{ route('sales-invoice.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan Invoice
                                </button>
                            </div>
                        </form>
                    </div> {{-- end card-body --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- Select2 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#id_so').select2({
                placeholder: "-- Pilih Sales Order --",
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>
@endsection
