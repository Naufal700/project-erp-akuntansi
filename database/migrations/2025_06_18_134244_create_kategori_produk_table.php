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
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->comment('');
            $table->integer('id', true);
            $table->string('kode_kategori', 50)->unique('kode_kategori');
            $table->string('nama_kategori', 100);
            $table->string('deskripsi', 65535)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
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
        Schema::dropIfExists('kategori_produk');
    }
};
