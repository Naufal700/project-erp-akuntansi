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
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->foreign(['kode_akun'], 'jurnal_umum_ibfk_1')->references(['kode_akun'])->on('coa')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->dropForeign('jurnal_umum_ibfk_1');
        });
    }
};
