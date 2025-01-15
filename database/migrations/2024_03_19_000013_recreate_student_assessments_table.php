<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop tabel lama jika ada
        Schema::dropIfExists('student_assessment_details');
        Schema::dropIfExists('student_assessments');

        // Buat tabel baru dengan struktur lengkap
        Schema::create('student_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_room_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('mata_pelajaran');
            $table->enum('jenis', ['Sumatif', 'Non-Sumatif']);
            $table->enum('kategori', ['Teori', 'Praktik']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Buat tabel detail
        Schema::create('student_assessment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->decimal('nilai', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_assessment_details');
        Schema::dropIfExists('student_assessments');
    }
}; 