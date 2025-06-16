<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NeracaLajurExport implements FromView
{
    protected $data;
    protected $periode;

    public function __construct($data, $periode)
    {
        $this->data = $data;
        $this->periode = $periode;
    }

    public function view(): View
    {
        return view('exports.neraca_lajur', [
            'data' => $this->data,
            'periode' => $this->periode,
        ]);
    }
}
