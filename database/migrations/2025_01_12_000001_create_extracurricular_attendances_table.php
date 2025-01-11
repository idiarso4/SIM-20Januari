<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('extracurricular_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_activity_id')
                ->constrained('extracurricular_activities')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])
                ->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['extracurricular_activity_id', 'student_id'], 'extra_attendance_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurricular_attendances');
    }
};
