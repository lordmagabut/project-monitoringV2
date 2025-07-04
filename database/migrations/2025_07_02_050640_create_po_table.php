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
        Schema::create('po', function (Blueprint $table) {
            $table->id();
            $table->string('no_po');
            $table->date('tanggal');
            $table->unsignedBigInteger('id_supplier');
            $table->string('nama_supplier');
            $table->decimal('total', 15, 2)->default(0);
            $table->unsignedBigInteger('id_proyek')->nullable();
            $table->unsignedBigInteger('id_perusahaan')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('po');
    }
};
