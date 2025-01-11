<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            // Add your modifications here
            // For example:
            // $table->string('new_column');
        });
    }

    public function down()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            // Reverse the modifications
            // For example:
            // $table->dropColumn('new_column');
        });
    }
}; 