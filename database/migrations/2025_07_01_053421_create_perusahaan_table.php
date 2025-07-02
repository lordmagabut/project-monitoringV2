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
    Schema::create('perusahaan', function (Blueprint $table) {
        $table->id(); // auto increment primary key
        $table->text('nama_perusahaan');
        $table->text('alamat');
        $table->string('email')->nullable();
        $table->bigInteger('no_telp')->nullable();
        $table->bigInteger('npwp')->nullable();
        $table->enum('tipe_perusahaan', ['UMKM', 'Kontraktor']);
        $table->timestamps();
    });
}

};
