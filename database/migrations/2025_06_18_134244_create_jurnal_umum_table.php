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
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('tanggal', 0);
            $table->string('kode_akun', 20)->index('kode_akun');
            $table->float('nominal_debit', 20)->nullable()->default(0);
            $table->float('nominal_kredit', 20)->nullable()->default(0);
            $table->string('keterangan', 65535)->nullable();
            $table->string('ref', 100)->nullable();
            $table->integer('ref_id')->nullable();
            $table->string('modul', 100)->nullable();
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
        Schema::dropIfExists('jurnal_umum');
    }
};
