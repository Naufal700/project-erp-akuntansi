@extends('adminlte::page')

@section('title', 'Tambah Pembayaran')

@section('content_header')
    <h1>Tambah Pembayaran</h1>
@stop

@section('content')
    <form action="{{ route('pembayaran-penjualan.store') }}" method="POST" id="formPembayaran" class="form-horizontal">
        @csrf
        <div class="card">
            <div class="card-body">

                {{-- Pilih Invoice --}}
                <div class="form-group row">
                    <label for="invoiceSelect" class="col-sm-2 col-form-label font-weight-bold">Invoice</label>
                    <div class="col-sm-10">
                        <select name="id_invoice" class="form-control" id="invoiceSelect" required>
                            <option value="">-- Pilih Invoice --</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->id }}">
                                    {{ $invoice->nomor_invoice }} — {{ $invoice->nama_customer }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_invoice')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <hr>

                {{-- Tanggal & Metode Pembayaran --}}
                <div class="form-group row">
                    <label for="tanggal" class="col-sm-2 col-form-label font-weight-bold">Tanggal Pembayaran</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required
                            value="{{ old('tanggal', date('Y-m-d')) }}">
                        @error('tanggal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <label for="metode" class="col-sm-2 col-form-label font-weight-bold">Metode Pembayaran</label>
                    <div class="col-sm-4">
                        <select name="id_metode_pembayaran" id="metode" class="form-control" required>
                            <option value="">-- Pilih Metode --</option>
                            @foreach ($metodePembayaran as $metode)
                                <option value="{{ $metode->id }}">{{ $metode->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_metode_pembayaran')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                {{-- Produk Belum Dibayar (Tabel) --}}
                <div class="form-group">
                    <label class="font-weight-bold">Produk Belum Dibayar</label>
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ced4da; border-radius: 4px;">
                        <table class="table table-sm table-bordered table-striped mb-0" id="tabelProdukBelumBayar"
                            style="min-width: 900px;">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Nama Produk</th>
                                    <th>Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Harga</th>
                                    <th>Diskon</th>
                                    <th>Harga Setelah Diskon</th>
                                    <th>PPN (11%)</th>
                                    <th>Harga Nett</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">
                                        Pilih invoice terlebih dahulu untuk menampilkan produk belum dibayar.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="3" class="text-right">Total :</td>
                                    <td id="totalHargaSatuan">Rp 0,00</td>
                                    <td id="totalTotalHarga">Rp 0,00</td>
                                    <td id="totalDiskon">Rp 0,00</td>
                                    <td id="totalHargaSetelahDiskon">Rp 0,00</td>
                                    <td id="totalPPN">Rp 0,00</td>
                                    <td id="totalHargaNett">Rp 0,00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @error('produk')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <hr>

                {{-- Jumlah Pembayaran (otomatis total produk yg dipilih) --}}
                <div class="form-group row align-items-center">
                    <label for="jumlahPembayaran" class="col-sm-2 col-form-label font-weight-bold">Jumlah Pembayaran</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="jumlah" id="jumlahPembayaranRaw" value="{{ old('jumlah') }}">
                        <input type="text" id="jumlahPembayaran" class="form-control" readonly value="Rp 0,00">
                        <small class="form-text text-muted">Jumlah otomatis dihitung berdasarkan produk yang
                            dipilih.</small>
                        @error('jumlah')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Pembayaran</button>
                <a href="{{ route('pembayaran-penjualan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>

    <script>
        // Fungsi format ke Rupiah
        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(number);
        };

        const invoiceSelect = document.getElementById('invoiceSelect');
        const tabelProduk = document.getElementById('tabelProdukBelumBayar').getElementsByTagName('tbody')[0];
        const jumlahPembayaranInput = document.getElementById('jumlahPembayaran');
        const jumlahPembayaranRawInput = document.getElementById('jumlahPembayaranRaw');

        // Elemen total di tfoot
        const totalHargaSatuanEl = document.getElementById('totalHargaSatuan');
        const totalTotalHargaEl = document.getElementById('totalTotalHarga');
        const totalDiskonEl = document.getElementById('totalDiskon');
        const totalHargaSetelahDiskonEl = document.getElementById('totalHargaSetelahDiskon');
        const totalPPNEl = document.getElementById('totalPPN');
        const totalHargaNettEl = document.getElementById('totalHargaNett');

        // Reset total baris footer
        function resetTotals() {
            totalHargaSatuanEl.textContent = formatRupiah(0);
            totalTotalHargaEl.textContent = formatRupiah(0);
            totalDiskonEl.textContent = formatRupiah(0);
            totalHargaSetelahDiskonEl.textContent = formatRupiah(0);
            totalPPNEl.textContent = formatRupiah(0);
            totalHargaNettEl.textContent = formatRupiah(0);
        }

        invoiceSelect.addEventListener('change', function() {
            const invoiceId = this.value;

            // Reset tabel & jumlah pembayaran & total
            tabelProduk.innerHTML = '';
            jumlahPembayaranInput.value = formatRupiah(0);
            jumlahPembayaranRawInput.value = 0;
            resetTotals();

            if (!invoiceId) {
                tabelProduk.innerHTML = `<tr>
                    <td colspan="9" class="text-center text-muted py-3">Pilih invoice terlebih dahulu untuk menampilkan produk belum dibayar.</td>
                </tr>`;
                return;
            }

            fetch(`/pembayaran-penjualan/invoice-produk-belum-bayar/${invoiceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.produk.length === 0) {
                            tabelProduk.innerHTML = `<tr>
                                <td colspan="9" class="text-center text-success py-3">Semua produk sudah dibayar.</td>
                            </tr>`;
                        } else {
                            data.produk.forEach((item, index) => {
                                const hargaSatuan = parseFloat(item.harga);
                                const qty = parseFloat(item.qty);
                                const totalHarga = hargaSatuan * qty;
                                const diskon = parseFloat(item.diskon);
                                const hargaSetelahDiskon = totalHarga - diskon;
                                const ppn = hargaSetelahDiskon * 0.11;
                                const hargaNett = hargaSetelahDiskon + ppn;

                                const row = document.createElement('tr');

                                // Checkbox kolom
                                const checkboxTd = document.createElement('td');
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.name = 'produk[]';
                                checkbox.value = item.id_detail;
                                checkbox.className = 'form-check-input';
                                checkbox.dataset.hargaNettTotal = hargaNett;
                                checkbox.dataset.hargaSatuan = hargaSatuan;
                                checkbox.dataset.totalHarga = totalHarga;
                                checkbox.dataset.diskon = diskon;
                                checkbox.dataset.hargaSetelahDiskon = hargaSetelahDiskon;
                                checkbox.dataset.ppn = ppn;
                                checkbox.id = `produk_${index}`;
                                checkboxTd.appendChild(checkbox);
                                row.appendChild(checkboxTd);

                                // Nama Produk
                                const namaTd = document.createElement('td');
                                namaTd.textContent = item.nama;
                                row.appendChild(namaTd);

                                // Qty
                                const qtyTd = document.createElement('td');
                                qtyTd.textContent = qty;
                                row.appendChild(qtyTd);

                                // Harga Satuan
                                const hargaSatuanTd = document.createElement('td');
                                hargaSatuanTd.textContent = formatRupiah(hargaSatuan);
                                row.appendChild(hargaSatuanTd);

                                // Total Harga
                                const totalHargaTd = document.createElement('td');
                                totalHargaTd.textContent = formatRupiah(totalHarga);
                                row.appendChild(totalHargaTd);

                                // Diskon
                                const diskonTd = document.createElement('td');
                                diskonTd.textContent = formatRupiah(diskon);
                                row.appendChild(diskonTd);

                                // Harga Setelah Diskon
                                const hargaSetelahDiskonTd = document.createElement('td');
                                hargaSetelahDiskonTd.textContent = formatRupiah(hargaSetelahDiskon);
                                row.appendChild(hargaSetelahDiskonTd);

                                // PPN
                                const ppnTd = document.createElement('td');
                                ppnTd.textContent = formatRupiah(ppn);
                                row.appendChild(ppnTd);

                                // Harga Nett
                                const hargaNettTd = document.createElement('td');
                                hargaNettTd.textContent = formatRupiah(hargaNett);
                                row.appendChild(hargaNettTd);

                                tabelProduk.appendChild(row);
                            });
                        }
                    } else {
                        tabelProduk.innerHTML = '';
                        alert('Gagal mengambil data produk: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error(error);
                    tabelProduk.innerHTML = '';
                    alert('Terjadi kesalahan saat mengambil data produk.');
                });
        });

        // Update jumlah pembayaran dan total saat checkbox berubah
        tabelProduk.addEventListener('change', function(e) {
            if (e.target && e.target.matches('input[type="checkbox"]')) {
                const checkedCheckboxes = tabelProduk.querySelectorAll('input[type="checkbox"]:checked');

                let totalHargaSatuan = 0;
                let totalTotalHarga = 0;
                let totalDiskon = 0;
                let totalHargaSetelahDiskon = 0;
                let totalPPN = 0;
                let totalHargaNett = 0;

                checkedCheckboxes.forEach(checkbox => {
                    totalHargaSatuan += parseFloat(checkbox.dataset.hargaSatuan);
                    totalTotalHarga += parseFloat(checkbox.dataset.totalHarga);
                    totalDiskon += parseFloat(checkbox.dataset.diskon);
                    totalHargaSetelahDiskon += parseFloat(checkbox.dataset.hargaSetelahDiskon);
                    totalPPN += parseFloat(checkbox.dataset.ppn);
                    totalHargaNett += parseFloat(checkbox.dataset.hargaNettTotal);
                });

                // Update total di footer tabel
                totalHargaSatuanEl.textContent = formatRupiah(totalHargaSatuan);
                totalTotalHargaEl.textContent = formatRupiah(totalTotalHarga);
                totalDiskonEl.textContent = formatRupiah(totalDiskon);
                totalHargaSetelahDiskonEl.textContent = formatRupiah(totalHargaSetelahDiskon);
                totalPPNEl.textContent = formatRupiah(totalPPN);
                totalHargaNettEl.textContent = formatRupiah(totalHargaNett);

                // Update jumlah pembayaran yang dikirim ke backend (harga nett)
                jumlahPembayaranInput.value = formatRupiah(totalHargaNett);
                jumlahPembayaranRawInput.value = totalHargaNett;
            }
        });
    </script>
@stop
