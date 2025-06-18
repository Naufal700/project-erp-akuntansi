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
        Schema::table('pembelian_invoice_detail', function (Blueprint $table) {
            $table->foreign(['id_invoice'], 'pembelian_invoice_detail_ibfk_1')->references(['id'])->on('pembelian_invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id_produk'], 'pembelian_invoice_detail_ibfk_2')->references(['id'])->on('produk')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembelian_invoice_detail', function (Blueprint $table) {
            $table->dropForeign('pembelian_invoice_detail_ibfk_1');
            $table->dropForeign('pembelian_invoice_detail_ibfk_2');
        });
    }
};
