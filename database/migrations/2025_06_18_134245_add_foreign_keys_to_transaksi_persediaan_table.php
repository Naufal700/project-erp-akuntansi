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
        Schema::table('transaksi_persediaan', function (Blueprint $table) {
            $table->foreign(['kode_produk'], 'transaksi_persediaan_ibfk_1')->references(['kode_produk'])->on('produk')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_persediaan', function (Blueprint $table) {
            $table->dropForeign('transaksi_persediaan_ibfk_1');
        });
    }
};
