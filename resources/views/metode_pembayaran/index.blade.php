@extends('adminlte::page')

@section('title', 'Metode Pembayaran')

@section('content_header')
    <h1>Metode Pembayaran</h1>
@stop

@section('content')
    <a href="{{ route('metode-pembayaran.create') }}" class="btn btn-primary mb-3">Tambah</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Akun</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($metodes as $m)
                <tr>
                    <td>{{ $m->nama }}</td>
                    <td>{{ ucfirst($m->tipe) }}</td>
                    <td>
                        @if ($m->coa)
                            {{ $m->coa->nama_akun }} ({{ $m->kode_akun }})
                        @else
                            Data Akun Tidak Ditemukan ({{ $m->kode_akun }})
                        @endif
                    </td>

                    <td>{{ $m->keterangan }}</td>
                    <td>
                        <a href="{{ route('metode-pembayaran.edit', $m->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('metode-pembayaran.destroy', $m->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin dihapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
