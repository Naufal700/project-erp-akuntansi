<h6 class="fw-bold text-{{ $color }}">{{ $title }}</h6>
<table class="table table-sm table-striped">
    @php $subtotal = 0; @endphp
    @foreach ($data as $item)
        @if ($item['saldo'] != 0)
            <tr>
                <td>{{ $item['nama_akun'] }}</td>
                <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
            </tr>
            @php $subtotal += $item['saldo']; @endphp
        @endif
    @endforeach
    <tr class="table-light fw-bold">
        <td>Subtotal {{ $title }}</td>
        <td class="text-end">Rp {{ number_format(abs($subtotal), 2, ',', '.') }}</td>
    </tr>
</table>
