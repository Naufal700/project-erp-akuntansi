<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NeracaSaldoExport implements FromView
{
    protected $data_neraca;

    public function __construct($data_neraca)
    {
        $this->data_neraca = $data_neraca;
    }

    public function view(): View
    {
        return view('neraca_saldo.export', [
            'data_neraca' => $this->data_neraca,
        ]);
    }
}
