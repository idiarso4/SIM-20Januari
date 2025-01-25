<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign key dari tabel student_scores terlebih dahulu
        Schema::table('student_scores', function (Blueprint $table) {
            $table->dropForeign(['assessment_id']);
        });

        // Drop tabel lama jika ada
        Schema::dropIfExists('assessments');

        // Buat tabel baru dengan struktur yang benar
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_room_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('mata_pelajaran');
            $table->string('kompetensi_dasar');
            $table->string('jenis_penilaian');
            $table->integer('bobot')->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Kembalikan foreign key ke student_scores
        Schema::table('student_scores', function (Blueprint $table) {
            $table->foreign('assessment_id')
                ->references('id')
                ->on('assessments')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        // Drop foreign key sebelum menghapus tabel
        Schema::table('student_scores', function (Blueprint $table) {
            $table->dropForeign(['assessment_id']);
        });

        Schema::dropIfExists('assessments');

        // Kembalikan foreign key setelah rollback
        Schema::table('student_scores', function (Blueprint $table) {
            $table->foreign('assessment_id')
                ->references('id')
                ->on('assessments')
                ->cascadeOnDelete();
        });
    }
}; 