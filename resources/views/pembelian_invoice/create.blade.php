@extends('adminlte::page')

@section('title', 'Buat Faktur Pembelian')

@section('content_header')
    <h1 class="font-weight-bold">Buat Faktur Pembelian</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <form action="{{ route('pembelian-invoice.store') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- FORM --}}
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="nomor_invoice">Nomor Faktur</label>
                        <input type="text" name="nomor_invoice" class="form-control" required
                            placeholder="Contoh: INV-20240613-001">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="id_po">Pilih Penerimaan (PO)</label>
                        <select name="id_po" id="id_po" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($poList as $po)
                                <option value="{{ $po->id }}">{{ $po->nomor_penerimaan }} -
                                    {{ $po->purchaseOrder->supplier->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tanggal">Tanggal Faktur</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="jatuh_tempo">Jatuh Tempo</label>
                        <input type="date" name="jatuh_tempo" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="nomor_faktur_pajak">Nomor Faktur Pajak</label>
                        <input type="text" name="nomor_faktur_pajak" class="form-control"
                            placeholder="Contoh: 010.001-23.12345678">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tanggal_faktur_pajak">Tanggal Faktur Pajak</label>
                        <input type="date" name="tanggal_faktur_pajak" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="gunakan_ppn">Gunakan PPN?</label>
                        <select name="gunakan_ppn" id="gunakan_ppn" class="form-control">
                            <option value="0">Tidak</option>
                            <option value="1">Ya (11%)</option>
                        </select>
                    </div>
                </div>

                {{-- INFO PO --}}
                <div class="form-row mb-3" id="info-po-supplier" style="display: none;">
                    <div class="col-md-6">
                        <strong>Nomor PO:</strong> <span id="text_po"></span><br>
                        <strong>Supplier:</strong> <span id="text_supplier"></span>
                    </div>
                </div>

                <h5 class="mt-4">Detail Produk</h5>
                <div id="produk-wrapper"></div>

                {{-- RINGKASAN --}}
                <div class="row mt-4">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="text-right">Subtotal:</th>
                                <td class="text-right" id="subtotal_text">Rp 0</td>
                            </tr>
                            <tr>
                                <th class="text-right">Total Diskon:</th>
                                <td class="text-right" id="diskon_total_text">Rp 0</td>
                            </tr>
                            <tr>
                                <th class="text-right">PPN (11%):</th>
                                <td class="text-right" id="ppn_text">Rp 0</td>
                            </tr>
                            <tr>
                                <th class="text-right font-weight-bold">Grand Total:</th>
                                <td class="text-right font-weight-bold" id="grand_total_text">Rp 0</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="form-group mt-4 text-right">
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="fas fa-save"></i> Simpan Faktur
                    </button>
                    <a href="{{ route('pembelian-invoice.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')
    <script>
        $('#id_po').change(function() {
            let id = $(this).val();
            if (id) {
                $.get('/api/penerimaan/' + id, function(data) {
                    $('#text_po').text(data.po.nomor_po);
                    $('#text_supplier').text(data.po.supplier.nama);
                    $('#info-po-supplier').show();

                    let html = `<table class="table table-bordered mt-3"><thead>
                        <tr><th>Produk</th><th>Qty</th><th>Harga PO</th><th>Harga Final</th><th>Diskon</th><th>Total</th></tr>
                    </thead><tbody>`;
                    data.details.forEach((item, i) => {
                        html += `<tr>
                        <td>${item.produk.nama}
                            <input type="hidden" name="produk[${i}][id]" value="${item.produk.id}">
                        </td>
                        <td><input type="number" name="produk[${i}][qty]" value="${item.qty_diterima}" class="form-control qty" readonly></td>
                        <td><input type="number" class="form-control" value="${item.harga_po}" readonly></td>
                        <td><input type="number" name="produk[${i}][harga]" value="${item.harga}" class="form-control harga"></td>
                        <td><input type="number" name="produk[${i}][diskon]" value="0" class="form-control diskon"></td>
                        <td class="text-right total_item">Rp 0</td>
                    </tr>`;
                    });
                    html += `</tbody></table>`;
                    $('#produk-wrapper').html(html);
                    hitungTotal();
                });
            }
        });

        $(document).on('input', '.qty, .harga, .diskon, #gunakan_ppn', hitungTotal);

        function hitungTotal() {
            let subtotal = 0,
                totalDiskon = 0,
                ppn = 0,
                grandTotal = 0;

            $('.qty').each(function(i) {
                let qty = parseFloat($(this).val() || 0);
                let harga = parseFloat($('.harga').eq(i).val() || 0);
                let diskon = parseFloat($('.diskon').eq(i).val() || 0);
                let total = (qty * harga) - diskon;

                subtotal += qty * harga;
                totalDiskon += diskon;
                $('.total_item').eq(i).text("Rp " + formatRupiah(total));
            });

            let setelahDiskon = subtotal - totalDiskon;
            if ($('#gunakan_ppn').val() == '1') {
                ppn = setelahDiskon * 0.11;
            }

            grandTotal = setelahDiskon + ppn;

            $('#subtotal_text').text("Rp " + formatRupiah(subtotal));
            $('#diskon_total_text').text("Rp " + formatRupiah(totalDiskon));
            $('#ppn_text').text("Rp " + formatRupiah(ppn));
            $('#grand_total_text').text("Rp " + formatRupiah(grandTotal));
        }

        function formatRupiah(angka) {
            return angka.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    </script>
@stop
