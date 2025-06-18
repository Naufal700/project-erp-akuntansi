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
        Schema::create('kontrabon', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('nomor_kontrabon', 50);
            $table->string('tanggal', 0);
            $table->integer('id_supplier')->index('id_supplier');
            $table->float('total', 20);
            $table->string('keterangan', 65535)->nullable();
            $table->enum('status', ['belum_dibayar', 'lunas', 'dicicil'])->nullable()->default('belum_dibayar');
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
        Schema::dropIfExists('kontrabon');
    }
};
