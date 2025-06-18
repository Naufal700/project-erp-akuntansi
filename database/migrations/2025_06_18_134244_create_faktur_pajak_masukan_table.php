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
        Schema::create('faktur_pajak_masukan', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->integer('id_invoice')->index('id_invoice');
            $table->string('nomor_faktur_pajak', 50);
            $table->string('tanggal_faktur_pajak', 0);
            $table->float('nilai_dpp', 20);
            $table->float('nilai_ppn', 20);
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
        Schema::dropIfExists('faktur_pajak_masukan');
    }
};
