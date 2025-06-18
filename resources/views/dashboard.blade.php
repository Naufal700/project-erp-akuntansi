@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-primary animate__animated animate__fadeInDown">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard ERP SiAkun
        </h1>
        <button id="toggle-darkmode" class="btn btn-sm btn-outline-dark">
            <i class="fas fa-moon"></i> Mode Gelap
        </button>
    </div>
@stop

@section('content')
    {{-- Welcome Message --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 bg-gradient-light animate__animated animate__fadeIn hover-shadow">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center">
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

    {{-- Ringkasan Card --}}
    <div class="row mb-4">
        @foreach ($cards as $card)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2 hover-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                                    {{ $card['title'] }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($card['value'], 0, ',', '.') }}
                                </div>
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

    {{-- Kolom 2: Chart dan Aktivitas --}}
    <div class="row">
        {{-- Chart --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 hover-shadow">
                <div class="card-header bg-primary text-white">
                    <strong>Ringkasan Piutang vs Hutang</strong>
                </div>
                <div class="card-body">
                    <canvas id="piutangHutangChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <style>
        .hover-shadow:hover {
            transform: scale(1.02);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
        }

        .icon-circle {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        body.dark-mode {
            background-color: #121212 !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .card {
            background-color: #1e1e1e;
            border-color: #333;
        }

        body.dark-mode .card-header {
            background-color: #2c2c2c !important;
            color: #ffffff;
        }

        body.dark-mode .list-group-item {
            background-color: #1e1e1e;
            color: #ccc;
        }

        body.dark-mode .btn-outline-dark {
            border-color: #ccc;
            color: #ccc;
        }

        body.dark-mode .btn-outline-dark:hover {
            background-color: #444;
        }

        body.dark-mode .text-dark {
            color: #e0e0e0 !important;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('piutangHutangChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Piutang', 'Hutang'],
                datasets: [{
                    data: [{{ $totalPiutang }}, {{ $totalHutang }}],
                    backgroundColor: ['#ffc107', '#dc3545'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: document.body.classList.contains('dark-mode') ? '#ccc' : '#333'
                        }
                    }
                }
            }
        });

        // Dark Mode Toggle
        const toggleBtn = document.getElementById('toggle-darkmode');
        const body = document.body;

        if (localStorage.getItem('dark-mode') === 'enabled') {
            body.classList.add('dark-mode');
        }

        toggleBtn.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            localStorage.setItem('dark-mode', body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        });
    </script>
@endpush
