<table>
    <thead>
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th>Saldo Awal (D)</th>
            <th>Saldo Awal (K)</th>
            <th>Mutasi (D)</th>
            <th>Mutasi (K)</th>
            <th>Saldo Setelah Mutasi (D)</th>
            <th>Saldo Setelah Mutasi (K)</th>
            <th>Penyesuaian (D)</th>
            <th>Penyesuaian (K)</th>
            <th>Setelah Penyesuaian (D)</th>
            <th>Setelah Penyesuaian (K)</th>
            <th>Laba Rugi (D)</th>
            <th>Laba Rugi (K)</th>
            <th>Neraca (D)</th>
            <th>Neraca (K)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item['kode_akun'] }}</td>
                <td>{{ $item['nama_akun'] }}</td>
                <td>{{ $item['saldo_awal_debit'] }}</td>
                <td>{{ $item['saldo_awal_kredit'] }}</td>
                <td>{{ $item['mutasi_debit'] }}</td>
                <td>{{ $item['mutasi_kredit'] }}</td>
                <td>{{ $item['neraca_saldo_debit'] }}</td>
                <td>{{ $item['neraca_saldo_kredit'] }}</td>
                <td>{{ $item['penyesuaian_debit'] }}</td>
                <td>{{ $item['penyesuaian_kredit'] }}</td>
                <td>{{ $item['neraca_sesudah_debit'] }}</td>
                <td>{{ $item['neraca_sesudah_kredit'] }}</td>
                <td>{{ $item['laba_rugi_debit'] }}</td>
                <td>{{ $item['laba_rugi_kredit'] }}</td>
                <td>{{ $item['neraca_debit'] }}</td>
                <td>{{ $item['neraca_kredit'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
