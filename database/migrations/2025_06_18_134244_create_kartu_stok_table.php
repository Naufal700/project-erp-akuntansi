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
        Schema::create('kartu_stok', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('tanggal', 0);
            $table->string('no_transaksi', 100);
            $table->integer('id_produk')->index('id_produk');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('sumber_tujuan', 150)->nullable();
            $table->integer('qty');
            $table->timestamp('created_at')->nullable()->useCurrent();
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
        Schema::dropIfExists('kartu_stok');
    }
};
