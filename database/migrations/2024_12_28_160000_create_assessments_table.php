<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assessments')) {
            Schema::create('assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('class_room_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['sumatif', 'non_sumatif']);
                $table->string('subject');
                $table->string('assessment_name');
                $table->date('date');
                $table->float('score');
                $table->text('description')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
}; 