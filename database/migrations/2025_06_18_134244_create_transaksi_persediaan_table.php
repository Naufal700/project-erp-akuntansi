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
        Schema::create('transaksi_persediaan', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('kode_produk', 50)->nullable()->index('kode_produk');
            $table->string('tanggal', 0)->nullable();
            $table->enum('jenis', ['saldo_awal', 'penerimaan', 'pengeluaran', 'retur']);
            $table->string('sumber', 100)->nullable();
            $table->integer('id_ref')->nullable();
            $table->integer('qty');
            $table->integer('qty_sisa')->nullable();
            $table->float('harga', 20);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_persediaan');
    }
};
