<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn("teaching_activities", "mata_pelajaran")) {
            Schema::table("teaching_activities", function (Blueprint $table) {
                $table->string("mata_pelajaran")->after("kelas_id");
            });
        }
    }

    public function down()
    {
        Schema::table("teaching_activities", function (Blueprint $table) {
            $table->dropColumn("mata_pelajaran");
        });
    }
};
