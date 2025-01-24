<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_journals', 'mata_pelajaran')) {
                $table->string('mata_pelajaran');
            }
            if (!Schema::hasColumn('teacher_journals', 'materi')) {
                $table->text('materi');
            }
            if (!Schema::hasColumn('teacher_journals', 'jam_mulai')) {
                $table->time('jam_mulai');
            }
            if (!Schema::hasColumn('teacher_journals', 'jam_selesai')) {
                $table->time('jam_selesai');
            }
            if (!Schema::hasColumn('teacher_journals', 'kegiatan')) {
                $table->text('kegiatan');
            }
            if (!Schema::hasColumn('teacher_journals', 'hasil')) {
                $table->text('hasil');
            }
            if (!Schema::hasColumn('teacher_journals', 'hambatan')) {
                $table->text('hambatan');
            }
            if (!Schema::hasColumn('teacher_journals', 'pemecahan_masalah')) {
                $table->text('pemecahan_masalah');
            }
        });
    }

    public function down()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            $table->dropColumn([
                'mata_pelajaran',
                'materi',
                'jam_mulai',
                'jam_selesai',
                'kegiatan',
                'hasil',
                'hambatan',
                'pemecahan_masalah'
            ]);
        });
    }
}; 