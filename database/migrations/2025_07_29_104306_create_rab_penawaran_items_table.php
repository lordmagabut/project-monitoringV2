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
        Schema::create('rab_penawaran_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_penawaran_section_id')->constrained('rab_penawaran_sections')->onDelete('cascade');
            $table->foreignId('rab_detail_id')->nullable()->constrained('rab_detail')->onDelete('set null'); // Link ke RAB Dasar Detail, nullable jika item tidak dari RAB dasar
            $table->string('kode');
            $table->string('deskripsi');
            $table->decimal('volume', 15, 4);
            $table->string('satuan', 20);
            $table->decimal('harga_satuan_dasar', 15, 2); // Harga satuan dari RAB Dasar
            $table->decimal('harga_satuan_calculated', 15, 2); // Harga satuan setelah profit/overhead (sebelum diskon tersebar)
            $table->decimal('harga_satuan_penawaran', 15, 2); // Harga satuan FINAL yang ditawarkan (setelah diskon tersebar)
            $table->decimal('total_penawaran_item', 15, 2); // Total untuk item ini

            // Opsional untuk pemisahan Material/Upah
            $table->decimal('harga_material_dasar_item', 15, 2)->nullable();
            $table->decimal('harga_upah_dasar_item', 15, 2)->nullable();
            $table->decimal('harga_material_calculated_item', 15, 2)->nullable();
            $table->decimal('harga_upah_calculated_item', 15, 2)->nullable();
            $table->decimal('harga_material_penawaran_item', 15, 2)->nullable();
            $table->decimal('harga_upah_penawaran_item', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_penawaran_items');
    }
};
