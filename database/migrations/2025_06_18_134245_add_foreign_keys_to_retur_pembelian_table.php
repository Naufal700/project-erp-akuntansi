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
        Schema::table('retur_pembelian', function (Blueprint $table) {
            $table->foreign(['id_invoice'], 'fk_retur_invoice')->references(['id'])->on('pembelian_invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id_supplier'], 'fk_retur_supplier')->references(['id'])->on('supplier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id_penerimaan'], 'retur_pembelian_ibfk_1')->references(['id'])->on('penerimaan_pembelian')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retur_pembelian', function (Blueprint $table) {
            $table->dropForeign('fk_retur_invoice');
            $table->dropForeign('fk_retur_supplier');
            $table->dropForeign('retur_pembelian_ibfk_1');
        });
    }
};
