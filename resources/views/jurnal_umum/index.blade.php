@extends('adminlte::page')

@section('title', 'Jurnal Umum')

@section('content_header')
    <h1 class="m-0 text-dark">Jurnal Umum</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .thead-dark.sticky-top {
            background-color: #343a40 !important;
            color: #fff;
            z-index: 10;
        }

        tbody tr:hover {
            background-color: #f2f6fc;
        }

        tfoot tr {
            font-weight: bold;
            background-color: #e9ecef;
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('jurnal_umum.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Buat Jurnal Manual
            </a>
            <form id="formDeleteSelected" method="POST" action="{{ route('jurnal_umum.deleteSelected') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirm('Yakin ingin menghapus jurnal yang dipilih?')">
                    <i class="fas fa-trash-alt"></i> Hapus Terpilih
                </button>
            </form>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('jurnal_umum.index') }}" class="row g-3 align-items-end mb-4">
                <div class="col-md-3">
                    <label for="tgl_dari" class="form-label">Tanggal Dari</label>
                    <input type="text" name="tgl_dari" id="tgl_dari" class="form-control"
                        value="{{ request('tgl_dari') }}" placeholder="Pilih tanggal mulai" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label for="tgl_sampai" class="form-label">Tanggal Sampai</label>
                    <input type="text" name="tgl_sampai" id="tgl_sampai" class="form-control"
                        value="{{ request('tgl_sampai') }}" placeholder="Pilih tanggal sampai" autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari</label>
                    <input type="search" name="search" id="search" class="form-control"
                        placeholder="Kode akun, keterangan, referensi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100">Filter</button>
                    <a href="{{ route('jurnal_umum.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-striped table-hover text-nowrap align-middle">
                    <thead class="thead-dark sticky-top">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th style="width:110px;">Tanggal</th>
                            <th style="width:120px;">Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Keterangan</th>
                            <th style="width:120px;">Referensi</th>
                            <th class="text-end" style="width:140px;">Debit (Rp)</th>
                            <th class="text-end" style="width:140px;">Kredit (Rp)</th>
                            <th style="width:110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurnals as $jurnal)
                            <tr>
                                <td>
                                    <input type="checkbox" name="ids[]" form="formDeleteSelected"
                                        value="{{ $jurnal->id }}">
                                </td>
                                <td>{{ tanggal_indonesia($jurnal->tanggal) }}</td>
                                <td>{{ $jurnal->kode_akun }}</td>
                                <td>{{ $jurnal->coa->nama_akun ?? '-' }}</td>
                                <td>{{ $jurnal->keterangan ?? '-' }}</td>
                                <td>{{ $jurnal->ref ?? '-' }}</td>
                                <td class="text-end">{{ number_format($jurnal->nominal_debit, 2, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($jurnal->nominal_kredit, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('jurnal_umum.edit', $jurnal->id) }}" class="btn btn-sm btn-warning"
                                        title="Edit Jurnal">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('jurnal_umum.destroy', $jurnal->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jurnal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Jurnal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted fst-italic">Tidak ada data jurnal umum yang
                                    sesuai.</td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($jurnals->count() > 0)
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Total Keseluruhan:</td>
                                <td class="text-end fw-bold">{{ number_format($totalDebit, 2, ',', '.') }}</td>
                                <td class="text-end fw-bold">{{ number_format($totalKredit, 2, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <div class="card-footer clearfix d-flex justify-content-end">
            {{ $jurnals->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#tgl_dari", {
            dateFormat: "Y-m-d",
            maxDate: "{{ request('tgl_sampai') ?? 'today' }}",
            allowInput: true,
        });

        flatpickr("#tgl_sampai", {
            dateFormat: "Y-m-d",
            minDate: "{{ request('tgl_dari') ?? null }}",
            maxDate: "today",
            allowInput: true,
        });

        document.getElementById('checkAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="ids[]"]');
            for (const cb of checkboxes) {
                cb.checked = this.checked;
            }
        });
    </script>
@endsection
