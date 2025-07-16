<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahCoaHppKeBarang extends Migration
{
    public function up()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->unsignedInteger('coa_hpp_id')->nullable()->after('coa_beban_id');

            $table->foreign('coa_hpp_id')->references('id')->on('coa')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['coa_hpp_id']);
            $table->dropColumn('coa_hpp_id');
        });
    }
}
