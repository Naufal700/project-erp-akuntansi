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
        Schema::create('coa', function (Blueprint $table) {
            $table->comment('');
            $table->string('kode_akun', 20)->primary();
            $table->string('nama_akun', 100);
            $table->enum('tipe_akun', ['Header', 'Kas', 'Bank', 'Piutang', 'Hutang', 'Persediaan', 'Aset Tetap', 'Modal', 'Pendapatan', 'Beban', 'HPP', 'Penyesuaian', 'Lainnya', 'Aset', 'Kewajiban'])->nullable();
            $table->string('parent_kode', 20)->nullable();
            $table->integer('level')->nullable();
            $table->float('saldo_awal', 15, 0);
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
        Schema::dropIfExists('coa');
    }
};
