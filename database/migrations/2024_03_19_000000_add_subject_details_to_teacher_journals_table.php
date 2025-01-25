<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            $table->string('mata_pelajaran')->nullable()->after('guru_id');
            $table->text('materi')->nullable()->after('mata_pelajaran');
            $table->time('jam_mulai')->nullable()->after('materi');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            $table->dropColumn([
                'mata_pelajaran',
                'materi',
                'jam_mulai',
                'jam_selesai'
            ]);
        });
    }
}; 