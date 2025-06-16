@extends('adminlte::page')

@section('title', 'Pesanan Penjualan')

@section('content_header')
    <h1>Pesanan Penjualan</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" class="form-inline mb-3">
                        <div class="input-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari nomor so..."
                                value="{{ request('search') }}" aria-label="Cari nama supplier">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" id="button-search">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        <a href="{{ route('sales_order.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                        <a href="{{ route('sales_order.create') }}" class="btn btn-success mr-2" title="Tambah SO">
                            <i class="fas fa-plus-circle"></i> Tambah SO
                        </a>
                        <a href="{{ route('sales_order.export') }}" class="btn btn-info mr-2" title="Export SO">
                            <i class="fas fa-download"></i> Export SO
                        </a>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nomor SO</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Total Harga</th>
                                    <th>Total Diskon</th>
                                    <th>Harga Bersih</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    @php
                                        // Hitung total harga sebelum diskon (harga * qty tanpa diskon)
                                        $totalHarga = $order->details->sum(function ($d) {
                                            return $d->harga * $d->qty;
                                        });

                                        // Hitung total diskon dalam rupiah dari detail
                                        $totalDiskon = $order->details->sum('diskon');

                                        // Harga bersih = total harga - total diskon
                                        $hargaBersih = $totalHarga - $totalDiskon;
                                    @endphp

                                    <tr>
                                        <td>{{ tanggal_indonesia($order->tanggal) }}</td>
                                        <td>{{ $order->nomor_so }}</td>
                                        <td>{{ $order->customer->nama }}</td>
                                        <td>Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($totalDiskon, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($hargaBersih, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($order->status) }}</td>
                                        <td>
                                            <a href="{{ route('sales_order.show', $order->id) }}"
                                                class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sales_order.edit', $order->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('sales_order.destroy', $order->id) }}" method="POST"
                                                style="display:inline-block"
                                                onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- end table-responsive --}}
                </div> {{-- end card-body --}}
            </div> {{-- end card --}}
        </div> {{-- end card-body --}}
    </div> {{-- end card --}}
@stop
