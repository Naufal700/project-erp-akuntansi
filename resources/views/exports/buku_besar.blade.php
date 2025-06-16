<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $akun)
            <tr>
                <td colspan="5"><strong>{{ $akun['coa']->kode_akun }} - {{ $akun['coa']->nama_akun }}</strong></td>
            </tr>
            <tr>
                <td colspan="4"><em>Saldo Awal</em></td>
                <td>{{ number_format($akun['saldo_awal'], 0, ',', '.') }}</td>
            </tr>
            @php $saldo = $akun['saldo_awal']; @endphp
            @foreach ($akun['jurnal'] as $row)
                @php
                    $saldo += $row->nominal_debit - $row->nominal_kredit;
                @endphp
                <tr>
                    <td>{{ $row->tanggal }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ number_format($row->nominal_debit, 0, ',', '.') }}</td>
                    <td>{{ number_format($row->nominal_kredit, 0, ',', '.') }}</td>
                    <td>{{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #f2f2f2;">
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ number_format($akun['total_debit'], 0, ',', '.') }}</strong></td>
                <td><strong>{{ number_format($akun['total_kredit'], 0, ',', '.') }}</strong></td>
                <td><strong>{{ number_format($akun['saldo_akhir'], 0, ',', '.') }}</strong></td>
            </tr>
        @endforeach
    </tbody>
</table>
