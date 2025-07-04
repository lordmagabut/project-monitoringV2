<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('po', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('keterangan');
            $table->timestamp('printed_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('po', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('printed_at');
        });
    }
};
