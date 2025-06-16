@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="text-primary"><i class="fas fa-tachometer-alt"></i> Dashboard ERP SiAkun</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-handshake"></i> Selamat datang di Sistem ERP Siakun!</h3>
                </div>
                <div class="card-body">
                    <p class="lead">
                        Sistem ERP yang dirancang untuk memudahkan pengelolaan akuntansi, persediaan, dan keuangan Anda.
                    </p>
                    <hr>
                    <p class="mb-0">
                        <strong>Dibuat oleh:</strong> Muhamad Naufal Istikhori
                    </p>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>© 2025 Siakun ERP - All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>
@stop

@section('sidebar')
    @parent
    {{-- Bisa tambahkan widget atau shortcut menu di sini --}}
@stop
