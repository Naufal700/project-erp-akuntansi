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
        Schema::table('pembayaran_pembelian', function (Blueprint $table) {
            $table->foreign(['id_kontrabon'], 'pembayaran_pembelian_ibfk_1')->references(['id'])->on('kontrabon')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran_pembelian', function (Blueprint $table) {
            $table->dropForeign('pembayaran_pembelian_ibfk_1');
        });
    }
};
