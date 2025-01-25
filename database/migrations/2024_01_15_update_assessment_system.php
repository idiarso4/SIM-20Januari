<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing tables if they exist
        Schema::dropIfExists('student_scores');
        Schema::dropIfExists('assessments');

        // Create assessments table
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['sumatif', 'non_sumatif']);
            $table->string('subject');
            $table->string('assessment_name');
            $table->date('date');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Create student_scores table
        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
            $table->timestamps();

            $table->unique(['assessment_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_scores');
        Schema::dropIfExists('assessments');
    }
}; 