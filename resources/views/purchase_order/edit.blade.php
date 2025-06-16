@extends('adminlte::page')

@section('title', 'Edit Purchase Order')

@section('content_header')
    <h1 class="font-weight-bold">Edit Purchase Order</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchase-order.update', $po->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Nomor PO</label>
                        <input type="text" class="form-control" value="{{ $po->nomor_po }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $po->tanggal }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Supplier</label>
                        <select name="id_supplier" class="form-control" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ $po->id_supplier == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>
                <h5 class="mb-3 font-weight-bold">Detail Produk</h5>

                <div id="produk-wrapper">
                    @foreach ($po->details as $i => $detail)
                        <div class="row mb-2 produk-row">
                            <div class="col-md-5">
                                <select name="produk[{{ $i }}][id_produk]" class="form-control" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($produks as $p)
                                        <option value="{{ $p->id }}"
                                            {{ $p->id == $detail->id_produk ? 'selected' : '' }}>
                                            {{ $p->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="produk[{{ $i }}][qty]" class="form-control"
                                    value="{{ $detail->qty }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="produk[{{ $i }}][harga]"
                                    class="form-control" value="{{ $detail->harga }}" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-danger remove-row">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-produk" class="btn btn-sm btn-secondary mb-3">+ Tambah Baris Produk</button>

                <div class="text-end">
                    <a href="{{ route('purchase-order.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update PO</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let index = {{ count($po->details) }};

        document.getElementById('add-produk').addEventListener('click', function() {
            const wrapper = document.getElementById('produk-wrapper');
            const row = document.createElement('div');
            row.classList.add('row', 'mb-2', 'produk-row');
            row.innerHTML = `
            <div class="col-md-5">
                <select name="produk[${index}][id_produk]" class="form-control" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($produks as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="produk[${index}][qty]" class="form-control" required placeholder="Qty">
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="produk[${index}][harga]" class="form-control" required placeholder="Harga">
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-danger remove-row">Hapus</button>
            </div>
        `;
            wrapper.appendChild(row);
            index++;
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-row')) {
                e.target.closest('.produk-row').remove();
            }
        });
    </script>
@endsection
