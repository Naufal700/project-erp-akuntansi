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
        Schema::create('pengiriman_penjualan', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('nomor_surat_jalan', 50)->unique('nomor_surat_jalan');
            $table->string('tanggal', 0);
            $table->integer('id_so')->index('id_so');
            $table->enum('status_pengiriman', ['draft', 'dikirim', 'diterima', 'dibatalkan'])->nullable()->default('dikirim');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengiriman_penjualan');
    }
};
