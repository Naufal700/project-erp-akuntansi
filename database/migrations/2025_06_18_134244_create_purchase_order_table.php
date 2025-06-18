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
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('nomor_po', 50)->unique('nomor_po');
            $table->string('tanggal', 0);
            $table->integer('id_supplier')->index('id_supplier');
            $table->enum('status', ['draft', 'diterima', 'selesai', 'batal'])->nullable()->default('draft');
            $table->float('total', 20)->nullable()->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order');
    }
};
