@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
@endphp

@section('adminlte_css_pre')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body.login-page {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
            color: #343a40;
        }

        .login-box {
            max-width: 420px;
            margin: 0 auto;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 2rem 1.8rem;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            text-align: center;
            color: #1877f2;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            font-weight: 500;
            transition: border 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #1877f2;
            box-shadow: none;
        }

        .input-group-text {
            background-color: #e9ecef;
        }

        .btn-login {
            background-color: #1877f2;
            border: none;
            font-weight: 600;
            padding: 0.6rem 1rem;
            color: #fff;
            font-size: 1rem;
            border-radius: 6px;
            transition: background 0.2s ease-in-out;
        }

        .btn-login:hover {
            background-color: #145ecf;
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }
    </style>
@stop

@section('auth_header')
    <h2>Masuk ke Akun Anda</h2>
@endsection

@section('auth_body')
    <form action="{{ $loginUrl }}" method="post">
        @csrf

        {{-- Email --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                value="{{ old('email') }}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="input-group mb-4">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Kata Sandi" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-login w-100">
                <i class="fas fa-sign-in-alt me-1"></i> Masuk
            </button>
        </div>
    </form>
@endsection

@section('auth_footer')
    {{-- Optional: Tambah link daftar atau lupa password --}}
    {{-- <p class="text-center mt-2"><a href="#">Lupa Kata Sandi?</a></p> --}}
@endsection
