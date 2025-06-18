@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="text-dark">
            <i class="fas fa-tachometer-alt mr-2 text-primary"></i> Dashboard
        </h1>
        <small class="text-muted">ERP Accounting System</small>
    </div>
@stop

@section('content')
    {{-- Welcome Message --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 bg-gradient-light animate__animated animate__fadeIn">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <i class="fas fa-handshake fa-3x text-primary mr-3"></i>
                        <div>
                            <h4 class="mb-1 font-weight-bold text-primary">Selamat Datang di SiAkun ERP</h4>
                            <p class="mb-0 text-muted">Kelola keuangan, pembelian, penjualan, dan akuntansi secara efisien
                                dan terintegrasi.</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="mb-1 text-muted">© 2025</p>
                        <strong class="text-primary">Muhamad Naufal Istikhori</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="row mb-4">
        @php
            $cards = [
                [
                    'title' => 'Total Penjualan',
                    'value' => number_format($totalPenjualan),
                    'color' => 'primary',
                    'icon' => 'shopping-cart',
                    'url' => 'sales_order',
                ],
                [
                    'title' => 'Purchase Order',
                    'value' => number_format($totalPO),
                    'color' => 'success',
                    'icon' => 'file-invoice-dollar',
                    'url' => 'purchase-order',
                ],
                [
                    'title' => 'Piutang',
                    'value' => number_format($totalPiutang),
                    'color' => 'warning',
                    'icon' => 'hand-holding-usd',
                    'url' => 'piutang',
                ],
                [
                    'title' => 'Hutang',
                    'value' => number_format($totalHutang),
                    'color' => 'danger',
                    'icon' => 'credit-card',
                    'url' => 'hutang-supplier',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2 hover-shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                                    {{ $card['title'] }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ $card['value'] }}</div>
                            </div>
                            <div class="icon-circle bg-{{ $card['color'] }} shadow">
                                <i class="fas {{ $card['icon'] }} text-white"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url($card['url']) }}"
                        class="card-footer bg-transparent border-top-0 small text-{{ $card['color'] }} text-right">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@stop
