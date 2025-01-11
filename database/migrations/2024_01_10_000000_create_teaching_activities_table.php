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
            $table->foreignId('guru_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('class_room_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('mata_pelajaran');
            $table->date('tanggal');
            $table->integer('jam_ke_mulai');
            $table->integer('jam_ke_selesai');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('materi');
            $table->string('media_dan_alat')->nullable();
            $table->text('important_notes')->nullable();
            $table->timestamps();

            // Satu guru hanya bisa mengajar satu mata pelajaran per kelas per jam
            $table->unique(['guru_id', 'class_room_id', 'tanggal', 'jam_ke_mulai'], 'teaching_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_activities');
    }
}; 