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
        Schema::table('sales_order_detail', function (Blueprint $table) {
            $table->foreign(['id_so'], 'sales_order_detail_ibfk_1')->references(['id'])->on('sales_order')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id_produk'], 'sales_order_detail_ibfk_2')->references(['id'])->on('produk')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_order_detail', function (Blueprint $table) {
            $table->dropForeign('sales_order_detail_ibfk_1');
            $table->dropForeign('sales_order_detail_ibfk_2');
        });
    }
};
