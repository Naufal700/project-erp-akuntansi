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
        Schema::table('penerimaan_pembelian_detail', function (Blueprint $table) {
            $table->foreign(['id_penerimaan'], 'penerimaan_pembelian_detail_ibfk_1')->references(['id'])->on('penerimaan_pembelian')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id_produk'], 'penerimaan_pembelian_detail_ibfk_2')->references(['id'])->on('produk')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penerimaan_pembelian_detail', function (Blueprint $table) {
            $table->dropForeign('penerimaan_pembelian_detail_ibfk_1');
            $table->dropForeign('penerimaan_pembelian_detail_ibfk_2');
        });
    }
};
