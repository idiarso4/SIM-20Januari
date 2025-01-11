<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            if (Schema::hasColumn('teaching_activities', 'teacher_id')) {
                $table->renameColumn('teacher_id', 'guru_id');
            }
        });
    }

    public function down()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            if (Schema::hasColumn('teaching_activities', 'guru_id')) {
                $table->renameColumn('guru_id', 'teacher_id');
            }
        });
    }
}; 