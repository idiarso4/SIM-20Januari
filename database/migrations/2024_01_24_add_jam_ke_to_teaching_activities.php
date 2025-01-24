<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('teaching_activities', 'jam_ke')) {
                $table->string('jam_ke')->nullable()->after('jam_ke_selesai');
            }
        });
    }

    public function down()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            if (Schema::hasColumn('teaching_activities', 'jam_ke')) {
                $table->dropColumn('jam_ke');
            }
        });
    }
}; 