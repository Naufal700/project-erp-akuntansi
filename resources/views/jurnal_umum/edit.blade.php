@extends('adminlte::page')

@section('title', 'Edit Jurnal Manual')

@section('content_header')
    <h1 class="mb-3">Edit Jurnal Manual</h1>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('jurnal_umum.update', $jurnal->id) }}" method="POST" id="formJurnal" novalidate>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" id="tanggal"
                        class="form-control @error('tanggal') is-invalid @enderror"
                        value="{{ old('tanggal', $jurnal->tanggal) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <label>Detail Jurnal <span class="text-danger">*</span></label>
                <table class="table table-bordered table-sm" id="tableJurnal">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 35%;">Kode Akun</th>
                            <th style="width: 20%;">Debit</th>
                            <th style="width: 20%;">Kredit</th>
                            <th style="width: 20%;">Keterangan</th>
                            <th style="width: 5%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jurnal->details as $index => $detail)
                            <tr>
                                <td>
                                    <select name="details[{{ $index }}][kode_akun]" class="form-control" required>
                                        <option value="" disabled>-- Pilih Akun --</option>
                                        @foreach ($coa as $akun)
                                            <option value="{{ $akun->kode_akun }}"
                                                {{ $detail->kode_akun == $akun->kode_akun ? 'selected' : '' }}>
                                                {{ $akun->kode_akun }} - {{ $akun->nama_akun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                        name="details[{{ $index }}][debit]" class="form-control debit"
                                        value="{{ $detail->debit }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                        name="details[{{ $index }}][kredit]" class="form-control kredit"
                                        value="{{ $detail->kredit }}">
                                </td>
                                <td>
                                    <input type="text" name="details[{{ $index }}][keterangan]"
                                        class="form-control" value="{{ $detail->keterangan }}">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-row" title="Hapus Baris">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right">
                                <button type="button" class="btn btn-primary btn-sm" id="btnAddRow">
                                    <i class="fas fa-plus"></i> Tambah Baris
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th><input type="text" id="totalDebit"
                                    class="form-control-plaintext text-right font-weight-bold" readonly></th>
                            <th><input type="text" id="totalKredit"
                                    class="form-control-plaintext text-right font-weight-bold" readonly></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>

                <div class="form-group">
                    <label for="ref">Referensi</label>
                    <input type="text" name="ref" id="ref" class="form-control"
                        value="{{ old('ref', $jurnal->ref) }}">
                </div>

                <div class="form-group">
                    <label for="modul">Modul</label>
                    <input type="text" name="modul" id="modul" class="form-control"
                        value="{{ old('modul', $jurnal->modul) }}">
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Update Jurnal
                </button>
                <a href="{{ route('jurnal_umum.index') }}" class="btn btn-outline-secondary ml-2">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let tableBody = $('#tableJurnal tbody');
            let rowCount = tableBody.find('tr').length;

            function updateTotals() {
                let totalDebit = 0;
                let totalKredit = 0;

                $('.debit').each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    totalDebit += val;
                });

                $('.kredit').each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    totalKredit += val;
                });

                $('#totalDebit').val(totalDebit.toFixed(2));
                $('#totalKredit').val(totalKredit.toFixed(2));
            }

            $('#btnAddRow').click(function() {
                let newRow = `
                    <tr>
                        <td>
                            <select name="details[${rowCount}][kode_akun]" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Akun --</option>
                                @foreach ($coa as $akun)
                                    <option value="{{ $akun->kode_akun }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" min="0" name="details[${rowCount}][debit]" class="form-control debit" value="0">
                        </td>
                        <td>
                            <input type="number" step="0.01" min="0" name="details[${rowCount}][kredit]" class="form-control kredit" value="0">
                        </td>
                        <td>
                            <input type="text" name="details[${rowCount}][keterangan]" class="form-control" value="">
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-danger btn-sm btn-remove-row" title="Hapus Baris">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.append(newRow);
                rowCount++;
                updateTotals();
            });

            tableBody.on('click', '.btn-remove-row', function() {
                $(this).closest('tr').remove();
                updateTotals();
            });

            tableBody.on('input', '.debit, .kredit', function() {
                updateTotals();
            });

            updateTotals();

            $('#formJurnal').on('submit', function(e) {
                let totalDebit = parseFloat($('#totalDebit').val()) || 0;
                let totalKredit = parseFloat($('#totalKredit').val()) || 0;

                if (totalDebit <= 0 || totalKredit <= 0) {
                    alert('Total Debit dan Kredit harus lebih dari 0.');
                    e.preventDefault();
                    return false;
                }

                if (totalDebit !== totalKredit) {
                    alert('Total Debit dan Kredit harus seimbang.');
                    e.preventDefault();
                    return false;
                }

                if ($('#tableJurnal tbody tr').length < 2) {
                    alert('Harus ada minimal dua baris jurnal.');
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
