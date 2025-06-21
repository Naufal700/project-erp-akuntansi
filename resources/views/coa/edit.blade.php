@extends('adminlte::page')

@section('title', 'Edit COA')

@section('content')
    <div class="container-fluid my-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-edit mr-2"></i>
                <h4 class="mb-0">Edit COA: {{ $coa->nama_akun }}</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><strong>{{ $error }}</strong></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('coa.update', $coa->kode_akun) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="kode_akun" class="form-label font-weight-bold">
                                    <i class="fas fa-barcode mr-1 text-secondary"></i> Kode Akun
                                </label>
                                <input type="text" class="form-control bg-light" id="kode_akun"
                                    value="{{ $coa->kode_akun }}" disabled>
                            </div>

                            <div class="form-group mb-4">
                                <label for="tipe_akun" class="form-label font-weight-bold">
                                    <i class="fas fa-layer-group mr-1 text-secondary"></i> Tipe Akun
                                </label>
                                <select name="tipe_akun" required class="form-control">
                                    @foreach (config('coa.tipe_akun') as $tipe)
                                        <option value="{{ $tipe }}"
                                            {{ old('tipe_akun', $coa->tipe_akun ?? '') == $tipe ? 'selected' : '' }}>
                                            {{ $tipe }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="level" class="form-label font-weight-bold">
                                    <i class="fas fa-sitemap mr-1 text-secondary"></i> Level (opsional)
                                </label>
                                <input type="number" class="form-control" id="level" name="level"
                                    value="{{ old('level', $coa->level) }}" min="1" placeholder="Masukkan Level">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="nama_akun" class="form-label font-weight-bold">
                                    <i class="fas fa-file-signature mr-1 text-secondary"></i> Nama Akun
                                </label>
                                <input type="text" class="form-control" id="nama_akun" name="nama_akun"
                                    value="{{ old('nama_akun', $coa->nama_akun) }}" required
                                    placeholder="Masukkan Nama Akun">
                            </div>

                            <div class="form-group mb-4">
                                <label for="parent_kode" class="form-label font-weight-bold">
                                    <i class="fas fa-network-wired mr-1 text-secondary"></i> Parent Kode (opsional)
                                </label>
                                <select class="form-control" id="parent_kode" name="parent_kode">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach ($parents as $kode => $nama)
                                        <option value="{{ $kode }}"
                                            {{ old('parent_kode', $coa->parent_kode) == $kode ? 'selected' : '' }}>
                                            {{ $kode }} - {{ $nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="saldo_awal_debit" class="form-label font-weight-bold">
                                    <i class="fas fa-wallet mr-1 text-secondary"></i> Saldo Awal Debit
                                </label>
                                <input type="number" class="form-control" id="saldo_awal_debit" name="saldo_awal_debit"
                                    value="{{ old('saldo_awal_debit', $coa->saldo_awal_debit > 0 ? $coa->saldo_awal_debit : 0) }}"
                                    min="0" placeholder="Masukkan Saldo Awal Debit">
                            </div>

                            <div class="form-group mb-4">
                                <label for="saldo_awal_kredit" class="form-label font-weight-bold">
                                    <i class="fas fa-wallet mr-1 text-secondary"></i> Saldo Awal Kredit
                                </label>
                                <input type="number" class="form-control" id="saldo_awal_kredit" name="saldo_awal_kredit"
                                    value="{{ old('saldo_awal_kredit', $coa->saldo_awal_kredit > 0 ? $coa->saldo_awal_kredit : 0) }}"
                                    min="0" placeholder="Masukkan Saldo Awal Kredit">
                            </div>

                            <div class="form-group mb-4">
                                <label for="periode_saldo_awal" class="form-label font-weight-bold">
                                    <i class="fas fa-calendar-alt mr-1 text-secondary"></i> Periode Saldo Awal
                                </label>
                                <input type="month" class="form-control" id="periode_saldo_awal" name="periode_saldo_awal"
                                    value="{{ old('periode_saldo_awal', $coa->periode_saldo_awal) }}" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary px-4 mr-3"
                                style="transition: background-color 0.3s ease;">
                                <i class="fas fa-save mr-2"></i> Update
                            </button>
                            <a href="{{ route('coa.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left mr-2"></i> Batal
                            </a>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
