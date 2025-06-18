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
        Schema::create('pembelian_invoice', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('nomor_invoice', 50)->unique('nomor_invoice');
            $table->string('nomor_faktur_pajak', 50)->nullable();
            $table->string('tanggal', 0);
            $table->string('tanggal_faktur_pajak', 0)->nullable();
            $table->integer('id_po')->index('id_po');
            $table->float('subtotal', 20)->default(0);
            $table->float('diskon', 20)->default(0);
            $table->float('ppn', 20)->default(0);
            $table->float('total', 20);
            $table->float('dibayar', 20)->default(0);
            $table->float('total_retur', 20)->default(0);
            $table->enum('status', ['belum_dibayar', 'belum_dikontrabon', 'dikontrabon', 'dibayar'])->nullable()->default('belum_dibayar');
            $table->string('jatuh_tempo', 0)->nullable();
            $table->string('tanggal_pembayaran', 0)->nullable();
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
        Schema::dropIfExists('pembelian_invoice');
    }
};
