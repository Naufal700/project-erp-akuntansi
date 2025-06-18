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
        Schema::create('jurnal_penyesuaian', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('tanggal', 0);
            $table->string('kode_akun', 20);
            $table->string('keterangan', 65535)->nullable();
            $table->double('nominal_debit')->nullable()->default(0);
            $table->double('nominal_kredit')->nullable()->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurnal_penyesuaian');
    }
};
