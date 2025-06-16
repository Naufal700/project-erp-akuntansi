@extends('adminlte::page')

@section('title', 'Buat Retur Pembelian')

@section('content_header')
    <h1 class="font-weight-bold">Form Retur Pembelian</h1>
@stop

@section('content')
    <form action="{{ route('retur-pembelian.store') }}" method="POST">
        @csrf

        {{-- HEADER RETUR --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white font-weight-bold">Informasi Retur</div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="nomor_retur">Nomor Retur</label>
                        <input type="text" name="nomor_retur" class="form-control" value="{{ $nextNumber }}" readonly
                            required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="id_penerimaan">Penerimaan Pembelian</label>
                        <select name="id_penerimaan" id="id_penerimaan" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($penerimaan as $p)
                                <option value="{{ $p->id }}">{{ $p->nomor_penerimaan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Supplier</label>
                        <input type="text" class="form-control" readonly id="supplier_nama" value="-">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="id_invoice">Faktur Pembelian (Opsional)</label>
                        <select name="id_invoice" class="form-control">
                            <option value="">-- Tidak Ada --</option>
                            @foreach ($invoice as $inv)
                                <option value="{{ $inv->id }}">{{ $inv->nomor_invoice }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>

        {{-- DETAIL BARANG --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white font-weight-bold">Detail Barang Diretur</div>
            <div class="card-body">
                <table class="table table-bordered" id="detail-retur-table">
                    <thead class="bg-light">
                        <tr>
                            <th>Produk</th>
                            <th>Qty Retur</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="retur-detail-body">
                        {{-- Baris akan di-generate otomatis dari penerimaan --}}
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-secondary" id="add-row">
                    <i class="fas fa-plus"></i> Tambah Baris
                </button>
            </div>
        </div>

        {{-- TOTAL DAN SUBMIT --}}
        <div class="form-group text-right">
            <label><strong>Total Retur:</strong></label>
            <input type="text" name="total" id="total-retur"
                class="form-control d-inline-block w-25 text-right font-weight-bold" readonly required>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">Simpan Retur</button>
            <a href="{{ route('retur-pembelian.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@stop

@section('js')
    <script>
        let rowCount = 0;
        let penerimaanData = @json($penerimaan);

        function updateSubtotal(row) {
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let harga = parseFloat(row.find('.harga').val()) || 0;
            let subtotal = qty * harga;
            row.find('.subtotal').val(subtotal.toFixed(2));
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total-retur').val(total.toFixed(2));
        }

        $('#add-row').click(function() {
            let newRow = `
                <tr>
                    <td>
                        <select name="detail[${rowCount}][id_produk]" class="form-control" required>
                            @foreach ($produk as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="detail[${rowCount}][qty_retur]" class="form-control qty" min="1" required></td>
                    <td><input type="number" name="detail[${rowCount}][harga_satuan]" class="form-control harga" step="0.01" required></td>
                    <td><input type="text" class="form-control subtotal text-right" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`;
            $('#retur-detail-body').append(newRow);
            rowCount++;
        });

        $(document).on('keyup change', '.qty, .harga', function() {
            let row = $(this).closest('tr');
            updateSubtotal(row);
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            updateTotal();
        });

        function updateSupplierAndInvoice() {
            let id = $('#id_penerimaan').val();
            let selected = penerimaanData.find(p => p.id == id);

            let supplier = selected?.purchase_order?.supplier?.nama || '-';
            let invoiceId = selected?.purchase_order?.invoice?.id || '';
            let invoiceDetail = selected?.purchase_order?.invoice?.detail || [];

            $('#supplier_nama').val(supplier);
            $('select[name="id_invoice"]').val(invoiceId);

            $('#retur-detail-body').empty();
            rowCount = 0;

            invoiceDetail.forEach((item, index) => {
                let produkId = item?.id_produk;
                let namaProduk = item?.produk?.nama || '-';
                let hargaSatuan = item?.harga || 0;

                let newRow = `
            <tr>
                <td>
                    <input type="hidden" name="detail[${index}][id_produk]" value="${produkId}">
                    <input type="text" class="form-control" value="${namaProduk}" readonly>
                </td>
                <td><input type="number" name="detail[${index}][qty_retur]" class="form-control qty" min="1" value="1" required></td>
                <td><input type="number" name="detail[${index}][harga_satuan]" class="form-control harga" step="0.01" value="${hargaSatuan}" required></td>
                <td><input type="text" class="form-control subtotal text-right" readonly></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;
                $('#retur-detail-body').append(newRow);
                rowCount++;
            });

            $('.qty, .harga').trigger('change');
        }
        $('#id_penerimaan').change(updateSupplierAndInvoice);
        $(document).ready(updateSupplierAndInvoice);
    </script>
@stop
