<?php

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return $amount < 0
            ? '(' . number_format(abs($amount), 2, ',', '.') . ')'
            : number_format($amount, 2, ',', '.');
    }
    function tanggal_indonesia($tanggal)
    {
        return \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
    }
    if (!function_exists('formatCurrency')) {
        function formatCurrency($amount)
        {
            return $amount < 0
                ? '(' . number_format(abs($amount), 2, ',', '.') . ')'
                : number_format($amount, 2, ',', '.');
        }
    }
}
