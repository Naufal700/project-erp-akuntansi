@extends('adminlte::page')

@section('title', 'Tambah SO')

@section('content_header')
    <h1 class="mb-4">Buat Pesanan Penjualan</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sales_order.store') }}" method="POST" class="mb-5">
                @csrf
                <div class="card shadow rounded">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-edit"></i> Form Input SO</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="id_customer">Customer</label>
                                <select name="id_customer" id="id_customer" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Customer --</option>
                                    @foreach ($customer as $cust)
                                        <option value="{{ $cust->id }}">{{ $cust->nama }}</option>
                                    @endforeach
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
                                    <tr>
                                        <td>
                                            <select name="produk[]" class="form-control produk-select" required>
                                                <option value="" selected disabled>-- Pilih Produk --</option>
                                                @foreach ($products as $prod)
                                                    <option value="{{ $prod->id }}" data-harga="{{ $prod->harga_jual }}"
                                                        data-stok="{{ $prod->stok }}"
                                                        data-stok-minimal="{{ $prod->stok_minimal }}">
                                                        {{ $prod->nama }} (Stok: {{ $prod->stok }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="qty[]" class="form-control" min="1"
                                                required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="harga[]"
                                                class="form-control harga-input" readonly required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="diskon[]" class="form-control"
                                                value="0" min="0" max="100">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row"
                                                title="Hapus Baris">&times;</button>
                                        </td>
                                    </tr>
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
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <style>
                .harga-input {
                    background-color: #e9ecef;
                    cursor: not-allowed;
                }
            </style>

            <script>
                function updateHarga(selectElement) {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const harga = selectedOption.dataset.harga;
                    const stok = parseInt(selectedOption.dataset.stok || '0');
                    const stokMinimal = parseInt(selectedOption.dataset.stokMinimal || '0');

                    const row = selectElement.closest('tr');
                    const hargaInput = row.querySelector('.harga-input');

                    if (harga) {
                        hargaInput.value = harga;
                    } else {
                        hargaInput.value = '';
                    }

                    // Tampilkan peringatan stok
                    if (stok <= 0 || stok < stokMinimal) {
                        alert('⚠️ Stok produk kosong atau di bawah stok minimal!');
                    }
                }

                // Pasang event listener awal
                document.querySelectorAll('.produk-select').forEach(select => {
                    select.addEventListener('change', function() {
                        updateHarga(this);
                    });
                });

                // Tombol tambah baris
                document.getElementById('addRow').addEventListener('click', function() {
                    let tableBody = document.querySelector('#produk_table tbody');
                    let row = tableBody.querySelector('tr').cloneNode(true);

                    // Reset input pada baris baru
                    row.querySelectorAll('input').forEach(input => input.value = '');
                    row.querySelector('select').selectedIndex = 0;

                    let newSelect = row.querySelector('.produk-select');
                    newSelect.addEventListener('change', function() {
                        updateHarga(this);
                    });

                    tableBody.appendChild(row);
                });

                // Hapus baris
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-row')) {
                        const rows = document.querySelectorAll('#produk_table tbody tr');
                        if (rows.length > 1) {
                            e.target.closest('tr').remove();
                        }
                    }
                });
            </script>
        </div>
    </div>
@stop
