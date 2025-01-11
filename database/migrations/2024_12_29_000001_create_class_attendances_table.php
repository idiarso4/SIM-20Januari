<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->foreignId('class_room_id')
                ->constrained('class_rooms')
                ->cascadeOnDelete();
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();
            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('jam_ke');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha', 'dispensasi'])
                ->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Satu siswa hanya bisa punya satu absensi per mata pelajaran per hari
            $table->unique(['student_id', 'subject_id', 'tanggal', 'jam_ke'], 'class_attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_attendances');
    }
}; 