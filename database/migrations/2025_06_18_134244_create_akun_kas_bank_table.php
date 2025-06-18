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
        Schema::create('akun_kas_bank', function (Blueprint $table) {
            $table->comment('');
            $table->string('kode_akun', 20)->primary();
            $table->string('nama_akun', 100)->nullable();
            $table->enum('tipe', ['kas', 'bank'])->nullable();
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
        Schema::dropIfExists('akun_kas_bank');
    }
};
