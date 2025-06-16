@extends('adminlte::page')

@section('title', 'Edit SO')

@section('content')
    <h1 class="mb-4">Edit Pesanan Penjualan</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales_order.update', $order->id) }}" method="POST" class="mb-5">
        @csrf
        @method('PUT')

        <div class="form-row mb-4">
            <div class="form-group col-md-4">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required
                    value="{{ old('tanggal', \Carbon\Carbon::parse($order->tanggal)->format('Y-m-d')) }}">
            </div>

            <div class="form-group col-md-4">
                <label for="id_customer">Customer</label>
                <select name="id_customer" id="id_customer" class="form-control" required>
                    <option value="" disabled>-- Pilih Customer --</option>
                    @foreach ($customer as $cust)
                        <option value="{{ $cust->id }}"
                            {{ old('id_customer', $order->id_customer) == $cust->id ? 'selected' : '' }}>
                            {{ $cust->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="" disabled {{ old('status', $order->status ?? '') == '' ? 'selected' : '' }}>--
                        Pilih Status --</option>
                    <option value="pending" {{ old('status', $order->status ?? '') == 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="approved" {{ old('status', $order->status ?? '') == 'approved' ? 'selected' : '' }}>
                        Approved</option>
                    <option value="rejected" {{ old('status', $order->status ?? '') == 'rejected' ? 'selected' : '' }}>
                        Rejected</option>
                </select>
            </div>
        </div>

        <h5 class="mb-3">Detail Produk</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="produk_table">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 40%;">Produk</th>
                        <th style="width: 15%;">Qty</th>
                        <th style="width: 20%;">Harga</th>
                        <th style="width: 15%;">Diskon (%)</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (old('produk'))
                        @foreach (old('produk') as $index => $oldProduk)
                            <tr>
                                <td>
                                    <select name="produk[]" class="form-control produk-select" required>
                                        <option value="" disabled>-- Pilih Produk --</option>
                                        @foreach ($produk as $prod)
                                            <option value="{{ $prod->id }}" data-harga="{{ $prod->harga_jual }}"
                                                {{ $oldProduk == $prod->id ? 'selected' : '' }}>
                                                {{ $prod->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control" min="1" required
                                        value="{{ old('qty')[$index] }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="harga[]" class="form-control harga-input"
                                        readonly required value="{{ old('harga')[$index] }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="diskon[]" class="form-control" min="0"
                                        max="100" value="{{ old('diskon.' . $index) }}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row"
                                        title="Hapus Baris">&times;</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($order->details as $index => $detail)
                            <tr>
                                <td>
                                    <select name="produk[]" class="form-control produk-select" required>
                                        <option value="" disabled>-- Pilih Produk --</option>
                                        @foreach ($produk as $prod)
                                            <option value="{{ $prod->id }}" data-harga="{{ $prod->harga_jual }}"
                                                {{ $detail->produk_id == $prod->id ? 'selected' : '' }}>
                                                {{ $prod->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control" min="1" required
                                        value="{{ $detail->qty }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="harga[]" class="form-control harga-input"
                                        readonly required value="{{ $detail->harga }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="diskon[]" class="form-control" min="0"
                                        max="100" value="{{ old('diskon.' . $index, $detail->diskon) }}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row"
                                        title="Hapus Baris">&times;</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-outline-secondary btn-sm mb-4" id="addRow">
            <i class="fas fa-plus"></i> Tambah Baris
        </button>

        <div class="d-flex">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>
        </div>
    </form>

    <style>
        /* Buat input harga lebih nyaman dilihat */
        .harga-input {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
    </style>

    <script>
        function updateHarga(selectElement) {
            const harga = selectElement.options[selectElement.selectedIndex].dataset.harga;
            const row = selectElement.closest('tr');
            if (harga) {
                row.querySelector('.harga-input').value = harga;
            } else {
                row.querySelector('.harga-input').value = '';
            }
        }

        // Pasang event listener untuk produk select yang sudah ada
        function pasangEventListenerProduk() {
            document.querySelectorAll('.produk-select').forEach(select => {
                select.removeEventListener('change', produkChangeHandler); // untuk mencegah event ganda
                select.addEventListener('change', produkChangeHandler);
            });
        }

        function produkChangeHandler() {
            updateHarga(this);
        }

        pasangEventListenerProduk();

        // Tombol tambah baris
        document.getElementById('addRow').addEventListener('click', function() {
            let tableBody = document.querySelector('#produk_table tbody');
            let row = tableBody.querySelector('tr').cloneNode(true);

            // Reset input pada baris baru
            row.querySelectorAll('input').forEach(input => input.value = '');
            row.querySelector('select').selectedIndex = 0;

            // Pasang event listener change ke select produk baru
            let newSelect = row.querySelector('.produk-select');
            newSelect.addEventListener('change', produkChangeHandler);

            tableBody.appendChild(row);
        });

        // Hapus baris produk
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const rows = document.querySelectorAll('#produk_table tbody tr');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                }
            }
        });
    </script>
@endsection
