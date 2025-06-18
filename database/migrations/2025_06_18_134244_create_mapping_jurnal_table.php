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
        Schema::create('mapping_jurnal', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('modul', 100)->nullable();
            $table->string('event', 100)->nullable();
            $table->string('kode_akun_debit', 20)->nullable()->index('kode_akun_debit');
            $table->string('kode_akun_kredit', 20)->nullable()->index('kode_akun_kredit');
            $table->string('keterangan', 65535)->nullable();
            $table->timestamps();
            $table->enum('arus_kas_kelompok', ['operasi', 'investasi', 'pendanaan'])->nullable();
            $table->enum('arus_kas_jenis', ['masuk', 'keluar'])->nullable();
            $table->string('arus_kas_keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mapping_jurnal');
    }
};
