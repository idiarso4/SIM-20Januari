<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_permits', function (Blueprint $table) {
            // Drop foreign keys yang ada
            $table->dropForeign(['student_id']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['piket_guru_id']);

            // Recreate foreign keys dengan benar
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->nullable();

            $table->foreignId('piket_guru_id')->nullable()->change();

            $table->foreign('piket_guru_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('student_permits', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['piket_guru_id']);

            // Restore original foreign keys if needed
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('piket_guru_id')->change();

            $table->foreign('piket_guru_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
}; 