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
        Schema::create('sales_invoice', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('nomor_invoice', 50)->unique('nomor_invoice');
            $table->string('tanggal', 0);
            $table->integer('id_so')->index('id_so');
            $table->float('total', 20);
            $table->integer('ppn')->default(0);
            $table->enum('status', ['belum_dibayar', 'belum_lunas', 'lunas'])->nullable()->default('belum_dibayar');
            $table->string('jatuh_tempo', 0)->nullable();
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
        Schema::dropIfExists('sales_invoice');
    }
};
