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
        Schema::create('pembayaran_penjualan', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->integer('id_invoice')->index('id_invoice');
            $table->unsignedInteger('id_metode_pembayaran')->nullable()->index('fk_metode_pembayaran');
            $table->string('tanggal', 0);
            $table->float('jumlah', 20);
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
        Schema::dropIfExists('pembayaran_penjualan');
    }
};
