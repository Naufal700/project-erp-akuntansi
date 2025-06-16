 @extends('adminlte::page')

 @section('title', 'Data Kontrabon')

 @section('content_header')
     <h1 class="font-weight-bold">Kontra Bon</h1>
 @stop

 @section('content')
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
                     <input type="text" name="search" class="form-control" placeholder="Cari Kontra Bon..."
                         value="{{ request('search') }}" aria-label="Cari Kantra Bon">
                     <div class="input-group-append">
                         <button class="btn btn-primary" type="submit" id="button-search">
                             <i class="fas fa-search"></i> Cari
                         </button>
                     </div>
                 </div>
                 <a href="{{ route('kontrabon.index') }}" class="btn btn-secondary mr-2">
                     <i class="fas fa-sync-alt"></i> Reset
                 </a>
                 <a href="{{ route('kontrabon.create') }}" class="btn btn-success mr-2" title="Tambah Kontra Bon">
                     <i class="fas fa-plus-circle"></i> Kontra Bon
                 </a>
                 <form method="GET" action="{{ route('pembelian-invoice.index') }}" class="form-inline">
                     <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                         class="form-control mr-2" placeholder="Dari Tanggal">
                     <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                         class="form-control mr-2" placeholder="Sampai Tanggal">
                     <select name="status" class="form-control mr-2">
                         <option value="">-- Semua Status --</option>
                         <option value="belum_dibayar" {{ request('status') == 'belum_dibayar' ? 'selected' : '' }}>Belum
                             Dibayar
                         </option>
                         <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                     </select>
                     <button type="submit" class="btn btn-secondary">Filter</button>
                 </form>
             </form>

             <div class="table-responsive">
                 <table class="table table-sm table-hover table-bordered align-middle">
                     <thead class="table-dark text-center">
                         <tr>
                             <th>No</th>
                             <th>Nomor Kontrabon</th>
                             <th>Tanggal</th>
                             <th>Supplier</th>
                             <th>Jumlah Faktur</th>
                             <th>Total Kontrabon</th>
                             <th>Status</th>
                             <th>Tanggal Pembayaran</th>
                             <th>Aksi</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($kontrabon as $item)
                             <tr>
                                 <td>{{ $loop->iteration }}</td>
                                 <td>{{ $item->nomor_kontrabon }}</td>
                                 <td>{{ tanggal_indonesia($item->tanggal) }}</td>
                                 <td>{{ $item->supplier->nama ?? '-' }}</td>
                                 <td>{{ $item->details->count() }}</td>
                                 <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                                 <td>
                                     @if ($item->status == 'belum_dibayar')
                                         <span class="badge bg-warning text-dark">Belum Dibayar</span>
                                     @else
                                         <span class="badge bg-success">Dibayar</span>
                                     @endif
                                 </td>
                                 <td>{{ tanggal_indonesia($item->tanggal) }}</td>
                                 <td class="text-center">
                                     <form action="{{ route('kontrabon.batal', $item->id) }}" method="POST"
                                         onsubmit="return confirm('Yakin ingin membatalkan kontrabon ini?');">
                                         @csrf
                                         {{-- Tombol Detail (icon info) --}}
                                         <a href="{{ route('kontrabon.show', $item->id) }}" class="btn btn-sm btn-info"
                                             title="Detail">
                                             <i class="fas fa-eye"></i>
                                         </a>

                                         {{-- Tombol Batal (icon times) --}}
                                         <button type="submit" class="btn btn-sm btn-danger" title="Batal">
                                             <i class="fas fa-times"></i>
                                         </button>
                                     </form>
                                 </td>
                             </tr>
                         @endforeach
                     </tbody>
                 </table>
             @stop
