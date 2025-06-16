<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class NeracaExport implements FromView
{
    protected $data;
    protected $tanggal;

    public function __construct($data, $tanggal)
    {
        $this->data = $data;
        $this->tanggal = $tanggal;
    }

    public function view(): View
    {
        return view('laporan.excel.neraca', [
            'aset_lancar' => $this->data['aset_lancar'],
            'aset_tetap' => $this->data['aset_tetap'],
            'kewajiban_jp' => $this->data['kewajiban_jp'],
            'kewajiban_pj' => $this->data['kewajiban_pj'],
            'modal' => $this->data['modal'],
            'laba_ditahan' => $this->data['laba_ditahan'],
            'laba_berjalan' => $this->data['laba_berjalan'],
            'tanggal' => $this->tanggal,
        ]);
    }
}
