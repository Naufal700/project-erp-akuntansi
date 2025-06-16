<table>
    <thead>
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th>Saldo Awal Debit</th>
            <th>Saldo Awal Kredit</th>
            <th>Mutasi Debit</th>
            <th>Mutasi Kredit</th>
            <th>Saldo Akhir Debit</th>
            <th>Saldo Akhir Kredit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_saldo_awal_debit = 0;
            $total_saldo_awal_kredit = 0;
            $total_mutasi_debit = 0;
            $total_mutasi_kredit = 0;
            $total_saldo_akhir_debit = 0;
            $total_saldo_akhir_kredit = 0;
        @endphp

        @foreach ($data_neraca as $akun)
            @php
                $saldo_awal_debit = $akun->saldo_awal > 0 ? $akun->saldo_awal : 0;
                $saldo_awal_kredit = $akun->saldo_awal < 0 ? abs($akun->saldo_awal) : 0;

                $mutasi_debit = $akun->total_debit;
                $mutasi_kredit = $akun->total_kredit;

                $saldo_akhir = $akun->saldo_awal + $mutasi_debit - $mutasi_kredit;
                $saldo_akhir_debit = $saldo_akhir > 0 ? $saldo_akhir : 0;
                $saldo_akhir_kredit = $saldo_akhir < 0 ? abs($saldo_akhir) : 0;

                $total_saldo_awal_debit += $saldo_awal_debit;
                $total_saldo_awal_kredit += $saldo_awal_kredit;
                $total_mutasi_debit += $mutasi_debit;
                $total_mutasi_kredit += $mutasi_kredit;
                $total_saldo_akhir_debit += $saldo_akhir_debit;
                $total_saldo_akhir_kredit += $saldo_akhir_kredit;
            @endphp
            <tr>
                <td>{{ $akun->kode_akun }}</td>
                <td>{{ $akun->nama_akun }}</td>
                <td>{{ number_format($saldo_awal_debit, 2, ',', '.') }}</td>
                <td>{{ number_format($saldo_awal_kredit, 2, ',', '.') }}</td>
                <td>{{ number_format($mutasi_debit, 2, ',', '.') }}</td>
                <td>{{ number_format($mutasi_kredit, 2, ',', '.') }}</td>
                <td>{{ number_format($saldo_akhir_debit, 2, ',', '.') }}</td>
                <td>{{ number_format($saldo_akhir_kredit, 2, ',', '.') }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>{{ number_format($total_saldo_awal_debit, 2, ',', '.') }}</strong></td>
            <td><strong>{{ number_format($total_saldo_awal_kredit, 2, ',', '.') }}</strong></td>
            <td><strong>{{ number_format($total_mutasi_debit, 2, ',', '.') }}</strong></td>
            <td><strong>{{ number_format($total_mutasi_kredit, 2, ',', '.') }}</strong></td>
            <td><strong>{{ number_format($total_saldo_akhir_debit, 2, ',', '.') }}</strong></td>
            <td><strong>{{ number_format($total_saldo_akhir_kredit, 2, ',', '.') }}</strong></td>
        </tr>
    </tbody>
</table>
