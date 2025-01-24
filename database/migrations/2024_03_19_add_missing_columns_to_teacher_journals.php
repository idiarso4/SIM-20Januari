<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_journals', 'tanggal')) {
                $table->date('tanggal')->nullable();
            }
            if (!Schema::hasColumn('teacher_journals', 'mata_pelajaran')) {
                $table->string('mata_pelajaran')->nullable();
            }
            if (!Schema::hasColumn('teacher_journals', 'kegiatan')) {
                $table->text('kegiatan')->nullable();
            }
            if (!Schema::hasColumn('teacher_journals', 'hasil')) {
                $table->text('hasil')->nullable();
            }
            if (!Schema::hasColumn('teacher_journals', 'hambatan')) {
                $table->text('hambatan')->nullable();
            }
            if (!Schema::hasColumn('teacher_journals', 'pemecahan_masalah')) {
                $table->text('pemecahan_masalah')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal',
                'mata_pelajaran',
                'kegiatan',
                'hasil',
                'hambatan',
                'pemecahan_masalah'
            ]);
        });
    }
}; 