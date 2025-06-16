@extends('layouts.app')

@section('title', 'Import Customer')

@section('content')
    <div class="container mt-4">
        <h1>Import Data Customer</h1>

        @include('layouts.partials.alerts')

        <form action="{{ route('customer.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
                <input type="file" name="file" id="file" accept=".xlsx, .xls"
                    class="form-control @error('file') is-invalid @enderror" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>
    </div>
@endsection
