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
        Schema::create('retur_pembelian', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('nomor_retur', 50)->unique('nomor_retur');
            $table->string('tanggal', 0);
            $table->integer('id_penerimaan')->index('id_penerimaan');
            $table->integer('id_supplier')->nullable()->index('fk_retur_supplier');
            $table->integer('id_invoice')->nullable()->index('fk_retur_invoice');
            $table->string('keterangan', 65535)->nullable();
            $table->float('total', 20);
            $table->float('nilai_nota_kredit', 15)->nullable();
            $table->enum('status', ['draft', 'diproses', 'nota_kredit', 'refund'])->nullable()->default('draft');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('retur_pembelian');
    }
};
