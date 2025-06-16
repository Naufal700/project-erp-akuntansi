@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
@endphp

@section('adminlte_css_pre')
    <style>
        body.login-page {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            color: #000;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #1877f2;
            font-weight: 700;
        }

        .input-group-text {
            background-color: #e9ecef;
        }

        input.form-control {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            font-weight: 500;
        }

        input.form-control:focus {
            border-color: #1877f2;
            box-shadow: none;
        }

        .btn-fb {
            background-color: #1877f2;
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 6px;
            padding: 0.6rem 1rem;
            font-size: 1rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-fb:hover {
            background-color: #166fe5;
        }
    </style>
@stop

@section('auth_header')
    <h2>Masuk ke Akun Anda</h2>
@endsection

@section('auth_body')
    <form action="{{ $loginUrl }}" method="post">
        @csrf

        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                value="{{ old('email') }}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
            @error('email')
                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="input-group mb-4">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Kata Sandi" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
            @error('password')
                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-fb">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </div>
    </form>
@endsection

@section('auth_footer')
    {{-- Kosongkan footer --}}
@endsection
