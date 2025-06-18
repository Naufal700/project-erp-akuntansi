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
        Schema::create('retur_pembelian_detail', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->integer('id_retur')->index('fk_detail_retur');
            $table->integer('id_produk')->index('fk_detail_produk');
            $table->integer('qty_retur');
            $table->float('harga_satuan', 20);
            $table->float('subtotal', 20)->nullable()->storedAs('(`qty_retur` * `harga_satuan`)');
            $table->string('keterangan', 65535)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retur_pembelian_detail');
    }
};
