@extends('adminlte::page')

@section('title', 'Edit Faktur Penjualan')

@section('content_header')
    <h1>Edit Faktur #{{ $invoice->nomor_invoice }}</h1>
@endsection

@section('content')
    <form action="{{ route('sales_invoice.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nomor Invoice</label>
            <input type="text" class="form-control" value="{{ $invoice->nomor_invoice }}" readonly>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal Invoice</label>
            <input type="date" class="form-control" name="tanggal" value="{{ $invoice->tanggal->format('Y-m-d') }}"
                required>
        </div>

        <div class="form-group">
            <label for="total">Total (Rp)</label>
            <input type="number" class="form-control" name="total" value="{{ $invoice->total }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" required>
                <option value="belum_dibayar" {{ $invoice->status == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar
                </option>
                <option value="dibayar" {{ $invoice->status == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jatuh_tempo">Jatuh Tempo</label>
            <input type="date" class="form-control" name="jatuh_tempo"
                value="{{ $invoice->jatuh_tempo?->format('Y-m-d') }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Faktur</button>
        <a href="{{ route('sales_invoice.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
