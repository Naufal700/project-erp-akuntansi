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
        Schema::table('produk_gudang', function (Blueprint $table) {
            $table->foreign(['id_produk'], 'produk_gudang_ibfk_1')->references(['id'])->on('produk')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id_gudang'], 'produk_gudang_ibfk_2')->references(['id'])->on('gudang')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produk_gudang', function (Blueprint $table) {
            $table->dropForeign('produk_gudang_ibfk_1');
            $table->dropForeign('produk_gudang_ibfk_2');
        });
    }
};
