<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rab_penawaran_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyek')->onDelete('cascade');
            $table->string('nama_penawaran');
            $table->date('tanggal_penawaran');
            $table->integer('versi')->default(1);
            $table->decimal('total_penawaran_bruto', 15, 2)->default(0); // Total sebelum diskon
            $table->decimal('discount_percentage', 5, 2)->nullable(); // Persentase diskon (misal 5.00 untuk 5%)
            $table->decimal('discount_amount', 15, 2)->nullable(); // Jumlah nominal diskon
            $table->decimal('final_total_penawaran', 15, 2)->default(0); // Total setelah diskon
            $table->string('status')->default('draft'); // draft, sent, accepted, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_penawaran_headers');
    }
};
