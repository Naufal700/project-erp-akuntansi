<?php

namespace App\Exports;

use App\Models\KartuStok;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class KartuStokExport implements FromView
{
    protected $data;
    protected $tanggalDari;
    protected $tanggalSampai;

    public function __construct($data, $tanggalDari, $tanggalSampai)
    {
        $this->data = $data;
        $this->tanggalDari = $tanggalDari;
        $this->tanggalSampai = $tanggalSampai;
    }

    public function view(): View
    {
        return view('kartu_stok.export_excel', [
            'kartuStok' => $this->data,
            'tanggalDari' => $this->tanggalDari,
            'tanggalSampai' => $this->tanggalSampai,
        ]);
    }
}
