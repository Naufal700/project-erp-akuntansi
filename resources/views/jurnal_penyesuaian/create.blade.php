@extends('adminlte::page')

@section('content')
    <h4>Tambah Jurnal Penyesuaian</h4>

    <form action="{{ route('jurnal-penyesuaian.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Akun</label>
            <select name="kode_akun" class="form-control" required>
                <option value="">-- Pilih Akun --</option>
                @foreach ($akun as $a)
                    <option value="{{ $a->kode_akun }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Nominal Debit</label>
            <input type="number" name="nominal_debit" class="form-control" step="0.01">
        </div>
        <div class="mb-3">
            <label>Nominal Kredit</label>
            <input type="number" name="nominal_kredit" class="form-control" step="0.01">
        </div>
        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
@endsection
