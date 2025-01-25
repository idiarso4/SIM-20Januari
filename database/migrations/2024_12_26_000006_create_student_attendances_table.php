<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->foreignId('class_id')
                ->constrained('classes')
                ->cascadeOnDelete();
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('extracurricular_activity_id')->nullable();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha', 'dispensasi'])
                ->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'class_id', 'subject_id'], 'student_attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
}; 