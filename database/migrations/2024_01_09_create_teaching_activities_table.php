<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users');
            $table->foreignId('kelas_id')->constrained('class_rooms');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->date('tanggal');
            $table->string('materi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_activities');
    }
};
