@section('content')
    <div class="container-fluid px-0">
        <ul class="nav nav-tabs" id="laporanTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="laba-tab" data-toggle="tab" href="#laba" role="tab">Laba Rugi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="neraca-tab" data-toggle="tab" href="#neraca" role="tab">Neraca</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="kas-tab" data-toggle="tab" href="#kas" role="tab">Arus Kas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="modal-tab" data-toggle="tab" href="#modal" role="tab">Perubahan Modal</a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="laporanTabsContent">
            <div class="tab-pane fade show active" id="laba" role="tabpanel">
                @include('laporan.laba_rugi', ['data' => $labaRugi])
            </div>
            <div class="tab-pane fade" id="neraca" role="tabpanel">
                @include('laporan.neraca', ['data' => $dataNeraca])
            </div>
            <div class="tab-pane fade" id="kas" role="tabpanel">
                @include('laporan.arus_kas', [
                    'arusKas' => $dataArusKas['arusKas'] ?? [],
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_akhir' => $tanggal_akhir,
                    'totalArusKas' => $dataArusKas['totalArusKas'] ?? [],
                    'kelompokUrutan' => $dataArusKas['kelompokUrutan'] ?? [],
                    'saldoAwal' => $dataArusKas['saldoAwal'] ?? 0,
                ])
            </div>
            <div class="tab-pane fade" id="modal" role="tabpanel">
                @include('laporan.perubahan_modal', [
                    'data' => $dataModal,
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_akhir' => $tanggal_akhir,
                ])
            </div>
        </div>
    </div>
@stop
