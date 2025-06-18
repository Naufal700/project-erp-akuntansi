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
        Schema::table('transaksi_kas', function (Blueprint $table) {
            $table->foreign(['kode_akun'], 'transaksi_kas_ibfk_1')->references(['kode_akun'])->on('akun_kas_bank')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_kas', function (Blueprint $table) {
            $table->dropForeign('transaksi_kas_ibfk_1');
        });
    }
};
