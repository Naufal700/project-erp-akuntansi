@extends('adminlte::page')

@section('title', 'Jurnal Penyesuaian')

@section('content_header')
    <h1>Jurnal Penyesuaian</h1>
@stop

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <form method="GET" class="d-flex align-items-center">
            <label for="periode" class="me-2">Periode:</label>
            <input type="month" name="periode" class="form-control me-2" value="{{ request('periode', date('Y-m')) }}">
            <button type="submit" class="btn btn-secondary">Tampilkan</button>
        </form>

        <a href="{{ route('jurnal-penyesuaian.create') }}" class="btn btn-primary">+ Tambah</a>
    </div>

    <table class="table table-bordered table-hover" id="tabelJurnal">
        <thead class="table-primary">
            <tr>
                <th style="width: 120px;">Tanggal</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th class="text-end">Debit</th>
                <th class="text-end">Kredit</th>
                <th>Keterangan</th>
                <th style="width: 100px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->kode_akun }}</td>
                    <td>{{ $item->akun->nama_akun ?? '-' }}</td>
                    <td class="text-end">{{ number_format($item->nominal_debit, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->nominal_kredit, 0, ',', '.') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>
                        <form action="{{ route('jurnal-penyesuaian.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus data ini?')" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#tabelJurnal').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>
@stop
