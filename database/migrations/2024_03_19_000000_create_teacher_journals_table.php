<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign key di student_assessments terlebih dahulu
        Schema::table('student_assessments', function (Blueprint $table) {
            if (Schema::hasColumn('student_assessments', 'teacher_journal_id')) {
                $table->dropForeign(['teacher_journal_id']);
                $table->dropColumn('teacher_journal_id');
            }
        });

        // Drop tabel jika sudah ada
        Schema::dropIfExists('teacher_journals');

        // Baru buat tabel teacher_journals
        Schema::create('teacher_journals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->text('kegiatan');
            $table->text('hasil');
            $table->text('hambatan');
            $table->text('pemecahan_masalah');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_journals');
        
        // Kembalikan kolom di student_assessments
        Schema::table('student_assessments', function (Blueprint $table) {
            if (!Schema::hasColumn('student_assessments', 'teacher_journal_id')) {
                $table->foreignId('teacher_journal_id')->nullable()->constrained();
            }
        });
    }
}; 