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
        Schema::table('kontrabon_detail', function (Blueprint $table) {
            $table->foreign(['id_kontrabon'], 'kontrabon_detail_ibfk_1')->references(['id'])->on('kontrabon')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['id_invoice'], 'kontrabon_detail_ibfk_2')->references(['id'])->on('pembelian_invoice')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kontrabon_detail', function (Blueprint $table) {
            $table->dropForeign('kontrabon_detail_ibfk_1');
            $table->dropForeign('kontrabon_detail_ibfk_2');
        });
    }
};
