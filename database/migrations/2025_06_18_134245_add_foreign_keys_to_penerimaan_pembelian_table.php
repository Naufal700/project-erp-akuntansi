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
        Schema::table('penerimaan_pembelian', function (Blueprint $table) {
            $table->foreign(['id_po'], 'penerimaan_pembelian_ibfk_1')->references(['id'])->on('purchase_order')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penerimaan_pembelian', function (Blueprint $table) {
            $table->dropForeign('penerimaan_pembelian_ibfk_1');
        });
    }
};
