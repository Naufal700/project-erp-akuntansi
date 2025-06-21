@extends('adminlte::page')

@section('title', 'Tambah COA')

@section('content')
    <div class="container-fluid my-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fas fa-plus-circle mr-2"></i> Tambah COA</h5>
            </div>
            <div class="card-body">
                {{-- Error Handling --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('coa.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_akun"><i class="fas fa-code"></i> Kode Akun</label>
                                <input type="text" class="form-control" id="kode_akun" name="kode_akun"
                                    placeholder="Contoh: 1-1101" value="{{ old('kode_akun') }}" required autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label for="tipe_akun"><i class="fas fa-list-alt"></i> Tipe Akun</label>
                                <select name="tipe_akun" required class="form-control">
                                    @foreach (config('coa.tipe_akun') as $tipe)
                                        <option value="{{ $tipe }}"
                                            {{ old('tipe_akun', $coa->tipe_akun ?? '') == $tipe ? 'selected' : '' }}>
                                            {{ $tipe }}
                                        </option>
                                    @endforeach
                                </select>

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="level"><i class="fas fa-layer-group"></i> Level</label>
                                <input type="number" class="form-control" id="level" name="level"
                                    value="{{ old('level') }}" placeholder="Contoh: 4">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_akun"><i class="fas fa-font"></i> Nama Akun</label>
                                <input type="text" class="form-control" id="nama_akun" name="nama_akun"
                                    placeholder="Contoh: Kas Kecil" value="{{ old('nama_akun') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="parent_kode"><i class="fas fa-sitemap"></i> Parent Kode (opsional)</label>
                                <select class="form-control" id="parent_kode" name="parent_kode">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach ($parents as $kode => $nama)
                                        @php $parentLevel = substr_count($kode, '.'); @endphp
                                        <option value="{{ $kode }}" data-level="{{ $parentLevel }}"
                                            {{ old('parent_kode') == $kode ? 'selected' : '' }}>
                                            {{ $kode }} - {{ $nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="saldo_awal_debit"><i class="fas fa-wallet"></i> Saldo Awal Debit</label>
                                <input type="number" class="form-control" id="saldo_awal_debit" name="saldo_awal_debit"
                                    value="{{ old('saldo_awal_debit', 0) }}" step="0.01">
                            </div>

                            <div class="form-group">
                                <label for="saldo_awal_kredit"><i class="fas fa-wallet"></i> Saldo Awal Kredit</label>
                                <input type="number" class="form-control" id="saldo_awal_kredit" name="saldo_awal_kredit"
                                    value="{{ old('saldo_awal_kredit', 0) }}" step="0.01">
                            </div>

                            <div class="form-group">
                                <label for="periode_saldo_awal"><i class="fas fa-calendar-alt"></i> Periode Saldo
                                    Awal</label>
                                <input type="month" class="form-control" id="periode_saldo_awal" name="periode_saldo_awal"
                                    value="{{ old('periode_saldo_awal') }}" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                            <a href="{{ route('coa.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#parent_kode').change(function() {
                var level = $(this).find(':selected').data('level');
                if (level !== undefined) {
                    $('#level').val(level + 1);
                } else {
                    $('#level').val('');
                }
            });

            // Trigger saat halaman dimuat jika sudah ada old('parent_kode')
            $('#parent_kode').trigger('change');
        });
    </script>
@endsection
