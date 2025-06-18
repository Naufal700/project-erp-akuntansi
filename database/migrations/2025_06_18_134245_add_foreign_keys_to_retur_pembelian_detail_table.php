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
        Schema::table('retur_pembelian_detail', function (Blueprint $table) {
            $table->foreign(['id_produk'], 'fk_detail_produk')->references(['id'])->on('produk')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id_retur'], 'fk_detail_retur')->references(['id'])->on('retur_pembelian')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retur_pembelian_detail', function (Blueprint $table) {
            $table->dropForeign('fk_detail_produk');
            $table->dropForeign('fk_detail_retur');
        });
    }
};
