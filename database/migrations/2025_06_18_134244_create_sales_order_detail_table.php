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
        Schema::create('sales_order_detail', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->integer('id_so')->index('id_so');
            $table->integer('id_produk')->index('id_produk');
            $table->integer('qty');
            $table->float('harga', 20);
            $table->integer('diskon')->nullable()->default(0);
            $table->float('subtotal', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_detail');
    }
};
