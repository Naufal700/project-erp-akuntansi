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
        Schema::create('sales_order', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('nomor_so', 50)->unique('nomor_so');
            $table->string('tanggal', 0);
            $table->integer('id_customer')->index('id_customer');
            $table->enum('status', ['pending', 'sudah invoice', 'rejected'])->nullable()->default('pending');
            $table->float('total', 20)->nullable()->default(0);
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
        Schema::dropIfExists('sales_order');
    }
};
