<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('student_assessments')) {
            Schema::create('student_assessments', function (Blueprint $table) {
                $table->id();
                $table->date('tanggal');
                $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('class_room_id')->constrained()->cascadeOnDelete();
                $table->string('mata_pelajaran');
                $table->enum('jenis', ['Sumatif', 'Non-Sumatif']);
                $table->enum('kategori', ['Teori', 'Praktik']);
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('student_assessment_details')) {
            Schema::create('student_assessment_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_assessment_id')->constrained()->cascadeOnDelete();
                $table->foreignId('student_id')->constrained()->cascadeOnDelete();
                $table->decimal('nilai', 5, 2);
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('student_assessment_details');
        Schema::dropIfExists('student_assessments');
    }
}; 