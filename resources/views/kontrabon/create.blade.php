@extends('adminlte::page')

@section('title', 'Buat Kontrabon')

@section('plugins.Select2', true)

@section('content_header')
    <h1 class="text-bold">📄 Buat Kontrabon</h1>
@stop

@section('content')
    <form id="form-kontrabon" action="{{ route('kontrabon.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                {{-- Informasi Kontrabon --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nomor Kontrabon</label>
                            <input type="text" name="nomor_kontrabon" class="form-control rounded-2"
                                value="{{ old('nomor_kontrabon', $generatedNumber ?? '') }}" required>
                            @error('nomor_kontrabon')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control rounded-2"
                                value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="id_supplier" id="supplier-select" class="form-control select2 rounded-2" required>
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_supplier')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="keterangan" class="form-control rounded-2" rows="3"
                                placeholder="Contoh: Kontrabon bulan Mei 2025">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel Invoice --}}
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="text-primary mb-3"><i class="fas fa-file-invoice"></i> Pilih Invoice</h5>
                        <div id="invoice-list" class="table-responsive">
                            <p class="text-muted">Silakan pilih supplier terlebih dahulu.</p>
                        </div>
                        <div class="mt-3">
                            <label>Total Kontrabon</label>
                            <div class="form-control bg-light d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Tagihan</span>
                                <strong class="text-danger fs-5" id="label-total">Rp 0</strong>
                            </div>
                            <input type="hidden" name="total">
                        </div>
                    </div>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-success btn-lg rounded-2">
                        <i class="fas fa-save"></i> Simpan Kontrabon
                    </button>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#supplier-select').select2({
                theme: 'bootstrap4',
                placeholder: "Pilih Supplier"
            });

            $('#supplier-select').on('change', function() {
                let supplierId = $(this).val();
                $('#invoice-list').html('<p class="text-muted">Memuat invoice...</p>');

                if (supplierId) {
                    $.ajax({
                        url: '{{ route('kontrabon.getInvoicesBySupplier') }}',
                        type: 'GET',
                        data: {
                            supplier_id: supplierId
                        },
                        success: function(invoices) {
                            let html = '';
                            if (Object.keys(invoices).length > 0) {
                                html += '<table class="table table-bordered table-sm">';
                                html +=
                                    '<thead class="table-primary"><tr><th width="5%">#</th><th>Nomor</th><th>Tanggal</th><th>Total</th></tr></thead><tbody>';
                                for (let i in invoices) {
                                    let inv = invoices[i];
                                    html += `<tr>
                                        <td><input type="checkbox" name="id_invoice[]" value="${inv.id}" class="invoice-check" data-total="${inv.total}"></td>
                                        <td>${inv.nomor_invoice}</td>
                                        <td>${inv.tanggal}</td>
                                        <td class="text-end">Rp ${parseFloat(inv.total).toLocaleString('id-ID')}</td>
                                    </tr>`;
                                }
                                html += '</tbody></table>';
                            } else {
                                html =
                                    '<p class="text-danger">Tidak ada faktur ditemukan untuk supplier ini.</p>';
                            }
                            $('#invoice-list').html(html);
                            updateTotal();
                        },
                        error: function() {
                            $('#invoice-list').html(
                                '<p class="text-danger">Gagal memuat faktur.</p>');
                        }
                    });
                } else {
                    $('#invoice-list').html(
                        '<p class="text-muted">Silakan pilih supplier terlebih dahulu.</p>');
                    updateTotal();
                }
            });

            $(document).on('change', '.invoice-check', function() {
                if ($(this).is(':checked')) {
                    $(this).closest('tr').addClass('table-success');
                } else {
                    $(this).closest('tr').removeClass('table-success');
                }
                updateTotal();
            });

            function updateTotal() {
                let total = 0;
                $('.invoice-check:checked').each(function() {
                    total += parseFloat($(this).data('total'));
                });
                $('[name="total"]').val(total);
                $('#label-total').text('Rp ' + total.toLocaleString('id-ID'));
            }

            $('[name="nomor_kontrabon"]').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#form-kontrabon').submit();
                }
            });
        });
    </script>
@stop
