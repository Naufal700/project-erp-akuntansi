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
        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('tanggal', 0);
            $table->string('kode_akun', 20)->index('kode_akun');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('sumber_tujuan', 100)->nullable();
            $table->float('nominal', 20);
            $table->string('keterangan', 65535)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_kas');
    }
};
