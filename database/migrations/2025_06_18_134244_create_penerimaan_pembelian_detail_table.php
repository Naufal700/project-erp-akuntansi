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
        Schema::create('penerimaan_pembelian_detail', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->integer('id_penerimaan')->index('id_penerimaan');
            $table->integer('id_produk')->index('id_produk');
            $table->integer('qty_diterima');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penerimaan_pembelian_detail');
    }
};
