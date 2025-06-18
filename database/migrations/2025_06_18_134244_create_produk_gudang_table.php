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
        Schema::create('produk_gudang', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->integer('id_produk');
            $table->integer('id_gudang')->index('id_gudang');
            $table->float('stok', 20)->nullable()->default(0);
            $table->float('stok_minimal', 20)->nullable()->default(0);
            $table->timestamp('last_updated')->useCurrentOnUpdate()->useCurrent();

            $table->unique(['id_produk', 'id_gudang'], 'id_produk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_gudang');
    }
};
