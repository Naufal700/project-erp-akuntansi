<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>No Transaksi</th>
            <th>Nama Produk</th>
            <th>Customer / Supplier</th>
            <th>Saldo Awal</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th>Saldo Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kartuStok as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->no_transaksi }}</td>
                <td>{{ $item->produk->nama ?? '-' }}</td>
                <td>{{ $item->sumber_tujuan ?? '-' }}</td>
                <td>{{ $item->saldo_awal }}</td>
                <td>{{ $item->masuk }}</td>
                <td>{{ $item->keluar }}</td>
                <td>{{ $item->saldo_akhir }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
