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
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->foreign(['id_metode_pembayaran'], 'fk_metode_pembayaran')->references(['id'])->on('metode_pembayaran')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['id_invoice'], 'pembayaran_penjualan_ibfk_1')->references(['id'])->on('sales_invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->dropForeign('fk_metode_pembayaran');
            $table->dropForeign('pembayaran_penjualan_ibfk_1');
        });
    }
};
