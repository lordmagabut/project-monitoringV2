<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usersapp', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->tinyInteger('buat_po')->default(0);
            $table->tinyInteger('edit_po')->default(0);
            $table->tinyInteger('hapus_po')->default(0);
            $table->tinyInteger('buat_ri')->default(0);
            $table->tinyInteger('edit_ri')->default(0);
            $table->tinyInteger('hapus_ri')->default(0);
            $table->tinyInteger('buat_inv')->default(0);
            $table->tinyInteger('edit_inv')->default(0);
            $table->tinyInteger('hapus_inv')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
