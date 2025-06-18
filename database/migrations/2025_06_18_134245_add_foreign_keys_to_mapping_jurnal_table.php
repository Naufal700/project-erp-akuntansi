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
        Schema::table('mapping_jurnal', function (Blueprint $table) {
            $table->foreign(['kode_akun_debit'], 'mapping_jurnal_ibfk_1')->references(['kode_akun'])->on('coa')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['kode_akun_kredit'], 'mapping_jurnal_ibfk_2')->references(['kode_akun'])->on('coa')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mapping_jurnal', function (Blueprint $table) {
            $table->dropForeign('mapping_jurnal_ibfk_1');
            $table->dropForeign('mapping_jurnal_ibfk_2');
        });
    }
};
