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
        Schema::table('pembelian_invoice', function (Blueprint $table) {
            $table->foreign(['id_po'], 'pembelian_invoice_ibfk_1')->references(['id'])->on('purchase_order')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembelian_invoice', function (Blueprint $table) {
            $table->dropForeign('pembelian_invoice_ibfk_1');
        });
    }
};
