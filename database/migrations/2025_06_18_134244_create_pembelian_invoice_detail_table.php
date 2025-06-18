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
        Schema::create('pembelian_invoice_detail', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->integer('id_invoice')->nullable()->index('id_invoice');
            $table->integer('id_produk')->nullable()->index('id_produk');
            $table->float('qty', 10)->nullable();
            $table->float('harga', 20)->nullable();
            $table->float('diskon', 20)->nullable();
            $table->float('total', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian_invoice_detail');
    }
};
