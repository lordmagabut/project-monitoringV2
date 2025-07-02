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
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek');
            $table->unsignedBigInteger('pemberi_kerja_id');
            $table->string('no_spk');
            $table->bigInteger('nilai_spk');
            $table->string('file_spk')->nullable();
            $table->enum('jenis_proyek', ['kontraktor', 'cost and fee']);
            $table->timestamps();
    
            // Relasi ke tabel pemberi_kerja
            $table->foreign('pemberi_kerja_id')->references('id')->on('pemberi_kerja')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proyek');
    }
};
