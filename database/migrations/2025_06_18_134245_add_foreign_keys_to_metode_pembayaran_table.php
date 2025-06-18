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
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->foreign(['kode_akun'], 'fk_metode_pembayaran_coa')->references(['kode_akun'])->on('coa')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->dropForeign('fk_metode_pembayaran_coa');
        });
    }
};
