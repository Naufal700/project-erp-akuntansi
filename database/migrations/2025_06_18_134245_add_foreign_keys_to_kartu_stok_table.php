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
        Schema::table('kartu_stok', function (Blueprint $table) {
            $table->foreign(['id_produk'], 'fk_kartu_stok_produk')->references(['id'])->on('produk')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kartu_stok', function (Blueprint $table) {
            $table->dropForeign('fk_kartu_stok_produk');
        });
    }
};
