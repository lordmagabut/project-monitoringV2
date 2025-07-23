<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToRabProgressTable extends Migration
{
    public function up()
    {
        Schema::table('rab_progress', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('progress_fisik');
        });
    }

    public function down()
    {
        Schema::table('rab_progress', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
