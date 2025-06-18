<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('kode_produk', 50)->unique('kode_produk');
            $table->string('nama', 150);
            $table->integer('id_kategori')->nullable();
            $table->string('satuan', 30)->nullable();
            $table->float('harga_beli', 20)->nullable();
            $table->float('harga_jual', 20)->nullable();
            $table->integer('stok_minimal')->nullable()->default(0);
            $table->integer('stok')->nullable()->default(0);
            $table->integer('saldo_awal_qty')->nullable()->default(0);
            $table->float('saldo_awal_harga', 20)->nullable()->default(0);
            $table->enum('tipe_produk', ['barang', 'jasa', 'biaya', 'non_stok'])->default('barang');
            $table->enum('tipe_stok', ['stok', 'non_stok', 'FIFO', 'Average'])->nullable()->default('stok');
            $table->integer('id_supplier')->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('lokasi_rak', 100)->nullable();
            $table->string('keterangan', 65535)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
};
