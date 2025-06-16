@extends('adminlte::page')

@section('title', 'Tambah Purchase Order')

@section('content_header')
    <h1 class="font-weight-bold">Tambah Purchase Order</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchase-order.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Nomor PO</label>
                        <input type="text" name="nomor_po" value="{{ $nomor_po }}" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Supplier</label>
                        <select name="id_supplier" class="form-control" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>
                <h5 class="mb-3 font-weight-bold">Detail Produk</h5>

                <div id="produk-wrapper">
                    <div class="row mb-2 produk-row">
                        <div class="col-md-5">
                            <select name="produk[0][id_produk]" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produks as $p)
                                    <option value="{{ $p->id }}" data-harga="{{ $p->harga_beli }}">
                                        {{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="produk[0][qty]" class="form-control" placeholder="Qty" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" name="produk[0][harga]" class="form-control"
                                placeholder="Harga" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-danger remove-row d-none">Hapus</button>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-produk" class="btn btn-sm btn-secondary mb-3">+ Tambah Baris Produk</button>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Simpan PO</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let index = 1;

        document.getElementById('add-produk').addEventListener('click', function() {
            const wrapper = document.getElementById('produk-wrapper');
            const row = document.createElement('div');
            row.classList.add('row', 'mb-2', 'produk-row');
            row.innerHTML = `
                <div class="col-md-5">
                    <select name="produk[${index}][id_produk]" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($produks as $p)
                            <option value="{{ $p->id }}" data-harga="{{ $p->harga_beli }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="produk[${index}][qty]" class="form-control" placeholder="Qty" required>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="produk[${index}][harga]" class="form-control" placeholder="Harga" required>
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

        // Autofill harga beli saat produk dipilih
        document.addEventListener('change', function(e) {
            if (e.target.name.includes('[id_produk]')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const harga = selectedOption.getAttribute('data-harga');
                if (harga) {
                    const parentRow = e.target.closest('.produk-row');
                    const hargaInput = parentRow.querySelector('input[name*="[harga]"]');
                    hargaInput.value = harga;
                }
            }
        });
    </script>
@endsection
