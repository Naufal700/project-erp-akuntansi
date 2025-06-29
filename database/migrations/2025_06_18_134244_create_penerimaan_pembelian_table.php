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
        Schema::create('penerimaan_pembelian', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('nomor_penerimaan', 50)->unique('nomor_penerimaan');
            $table->string('tanggal', 0);
            $table->integer('id_po')->index('id_po');
            $table->enum('status', ['diterima', 'dibatalkan', 'belum_faktur', 'sudah_faktur'])->nullable()->default('diterima');
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
        Schema::dropIfExists('penerimaan_pembelian');
    }
};
