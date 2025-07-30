<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('rab_penawaran_headers', function (Blueprint $table) {
            // Menambahkan kolom 'area' sebagai string yang bisa null
            $table->string('area')->nullable()->after('proyek_id');
            // Menambahkan kolom 'spesifikasi' sebagai teks yang bisa null
            $table->text('spesifikasi')->nullable()->after('area');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('rab_penawaran_headers', function (Blueprint $table) {
            // Menghapus kolom 'area' dan 'spesifikasi' jika migrasi dibatalkan
            $table->dropColumn(['area', 'spesifikasi']);
        });
    }
};
