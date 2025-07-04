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
    Schema::create('po_detail', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('po_id');
        $table->string('kode_item');
        $table->string('uraian');
        $table->integer('qty');
        $table->string('uom');
        $table->decimal('harga', 15, 2);
        $table->decimal('diskon_persen', 5, 2)->default(0);
        $table->decimal('diskon_rupiah', 15, 2)->default(0);
        $table->decimal('ppn_persen', 5, 2)->default(0);
        $table->decimal('ppn_rupiah', 15, 2)->default(0);
        $table->decimal('total', 15, 2)->default(0);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('po_detail');
    }
};
