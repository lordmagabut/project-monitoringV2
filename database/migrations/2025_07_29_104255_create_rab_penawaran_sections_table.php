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
        Schema::create('rab_penawaran_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_penawaran_header_id')->constrained('rab_penawaran_headers')->onDelete('cascade');
            $table->foreignId('rab_header_id')->constrained('rab_header')->onDelete('cascade'); // Link ke RAB Dasar Header
            $table->decimal('profit_percentage', 5, 2)->default(0);
            $table->decimal('overhead_percentage', 5, 2)->default(0);
            $table->decimal('total_section_penawaran', 15, 2)->default(0); // Total untuk section ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_penawaran_sections');
    }
};