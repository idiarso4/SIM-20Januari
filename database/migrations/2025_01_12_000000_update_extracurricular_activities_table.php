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
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop tabel extracurricular_activity_attendances terlebih dahulu
        Schema::dropIfExists('extracurricular_activity_attendances');
        
        // Kemudian baru drop dan recreate extracurricular_activities
        Schema::dropIfExists('extracurricular_activities');
        Schema::create('extracurricular_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained()->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->string('materi');
            $table->text('dokumentasi')->nullable();
            $table->timestamps();
        });

        // Buat ulang tabel extracurricular_activity_attendances
        Schema::create('extracurricular_activity_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_activity_id')
                ->constrained('extracurricular_activities', 'id', 'extra_act_att_activity_id_foreign')
                ->onDelete('cascade');
            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha']);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            // Tambahkan unique constraint
            $table->unique(
                ['extracurricular_activity_id', 'student_id'],
                'extra_attendance_unique'
            );
        });

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop tabel dalam urutan terbalik
        Schema::dropIfExists('extracurricular_activity_attendances');
        Schema::dropIfExists('extracurricular_activities');

        // Buat ulang tabel dengan struktur lama jika diperlukan
        // ...

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 