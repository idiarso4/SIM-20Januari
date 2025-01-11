<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_activity_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained();
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha']);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_activity_attendances');
    }
}; 