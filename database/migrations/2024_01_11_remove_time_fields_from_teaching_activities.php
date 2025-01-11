<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->dropColumn(['jam_ke_mulai', 'jam_ke_selesai']);
        });
    }

    public function down()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->integer('jam_ke_mulai')->after('materi');
            $table->integer('jam_ke_selesai')->after('jam_ke_mulai');
        });
    }
}; 