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
        Schema::create('pembayaran_pembelian', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->integer('id_kontrabon')->index('id_kontrabon');
            $table->string('tanggal', 0);
            $table->string('metode', 50)->nullable();
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
        Schema::dropIfExists('pembayaran_pembelian');
    }
};
