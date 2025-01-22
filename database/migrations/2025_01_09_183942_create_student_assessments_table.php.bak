<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('guru_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('class_room_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('teacher_journal_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('mata_pelajaran');
            $table->enum('jenis_penilaian', [
                'teori',
                'praktik',
                'tugas',
                'uh',
                'uts',
                'uas'
            ]);
            $table->unsignedTinyInteger('attempt')->default(1);
            $table->string('kompetensi_dasar');
            $table->date('tanggal');
            $table->decimal('nilai', 5, 2);
            $table->text('deskripsi')->nullable();
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
            
            // Satu siswa hanya bisa memiliki 5 kesempatan per jenis penilaian
            $table->unique([
                'student_id',
                'guru_id',
                'mata_pelajaran',
                'jenis_penilaian',
                'attempt'
            ], 'assessment_attempt_unique');
        });

        // Menambahkan constraint untuk attempt maksimal 5 menggunakan DB statement
        DB::statement('ALTER TABLE student_assessments ADD CONSTRAINT check_attempt CHECK (attempt <= 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_assessments');
    }
};
