@extends('adminlte::page')

@section('title', 'Tambah Penerimaan')

@section('content_header')
    <h1 class="font-weight-bold mb-3">Tambah Penerimaan Barang</h1>
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger shadow">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('penerimaan.store') }}" method="POST" onsubmit="return validateQty();">
                @csrf

                {{-- Informasi PO dan Tanggal --}}
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="tanggal">Tanggal Penerimaan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control"
                            value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="id_po">Pilih Purchase Order <span class="text-danger">*</span></label>
                        <select name="id_po" class="form-control select2" required>
                            <option value="">-- Pilih PO --</option>
                            @foreach ($poList as $po)
                                <option value="{{ $po->id }}" {{ old('id_po') == $po->id ? 'selected' : '' }}>
                                    {{ $po->nomor_po }} - {{ $po->supplier->nama ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Detail Barang --}}
                <h5 class="mt-4 mb-2">Detail Barang Diterima</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="detail-table">
                        <thead class="thead-light text-center">
                            <tr>
                                <th style="width: 40%">Produk</th>
                                <th style="width: 20%">Qty PO</th>
                                <th style="width: 20%">Qty Diterima</th>
                                <th style="width: 10%">
                                    <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="id_produk[]" class="form-control select2" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($produkList as $produk)
                                            <option value="{{ $produk->id }}">{{ $produk->nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="qty_po[]" class="form-control" readonly>
                                </td>
                                <td>
                                    <input type="number" name="qty_diterima[]" class="form-control" min="1"
                                        required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Tombol --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </form>
        </div>
    </div>

@stop

@section('js')
    <script>
        $(document).ready(function() {
            const allProduk = @json($produkList);

            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih --',
                width: '100%'
            });

            $('select[name="id_po"]').on('change', function() {
                const id_po = this.value;
                const tableBody = $('#detail-table tbody');
                if (!id_po) return;

                fetch(`/get-produk-po/${id_po}`)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.html('');
                        data.forEach(item => {
                            const row = `
    <tr>
        <td>
            <select class="form-control select2" disabled>
                <option value="${item.id_produk}" selected>${item.nama}</option>
            </select>
            <input type="hidden" name="id_produk[]" value="${item.id_produk}">
        </td>
        <td>
            <input type="number" name="qty_po[]" class="form-control" value="${item.qty}" readonly>
        </td>
        <td>
            <input type="number" name="qty_diterima[]" class="form-control" value="${item.qty}" min="1" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>`;
                            tableBody.append(row);
                        });

                        $('.select2').select2({
                            theme: 'bootstrap4',
                            width: '100%'
                        });
                    });
            });
        });

        function addRow() {
            const table = document.querySelector('#detail-table tbody');
            const row = table.querySelector('tr');
            const newRow = row.cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(el => {
                el.value = '';
                if (el.name === 'qty_po[]') el.readOnly = true;
                if (el.name === 'id_produk[]') el.disabled = false;
            });

            table.appendChild(newRow);
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }

        function removeRow(button) {
            const row = button.closest('tr');
            const table = document.querySelector('#detail-table tbody');
            if (table.rows.length > 1) row.remove();
        }

        function validateQty() {
            const qtyDiterima = document.querySelectorAll('input[name="qty_diterima[]"]');
            const qtyPO = document.querySelectorAll('input[name="qty_po[]"]');

            for (let i = 0; i < qtyDiterima.length; i++) {
                const diterima = parseInt(qtyDiterima[i].value);
                const po = parseInt(qtyPO[i].value);
                if (diterima > po) {
                    alert("Qty diterima tidak boleh melebihi Qty PO.");
                    return false;
                }
            }
            return true;
        }
    </script>
@stop
