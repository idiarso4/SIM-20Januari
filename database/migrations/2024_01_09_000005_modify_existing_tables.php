<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dan tambah kolom yang mungkin belum ada di tabel assessments
        if (Schema::hasTable('assessments')) {
            Schema::table('assessments', function (Blueprint $table) {
                if (!Schema::hasColumn('assessments', 'student_id')) {
                    $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
                }
                // tambahkan pengecekan kolom lainnya jika diperlukan
            });
        }

        // Cek dan tambah kolom yang mungkin belum ada di tabel attendances
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'end_latitude')) {
                    $table->double('end_latitude')->nullable();
                }
                if (!Schema::hasColumn('attendances', 'end_longitude')) {
                    $table->double('end_longitude')->nullable();
                }
                // tambahkan pengecekan kolom lainnya jika diperlukan
            });
        }

        // Cek dan tambah kolom yang mungkin belum ada di tabel teaching_activities
        if (Schema::hasTable('teaching_activities')) {
            Schema::table('teaching_activities', function (Blueprint $table) {
                if (!Schema::hasColumn('teaching_activities', 'jam_mulai')) {
                    $table->time('jam_mulai')->nullable();
                }
                if (!Schema::hasColumn('teaching_activities', 'jam_selesai')) {
                    $table->time('jam_selesai')->nullable();
                }
                // tambahkan pengecekan kolom lainnya jika diperlukan
            });
        }
    }

    public function down(): void
    {
        // Hapus kolom yang ditambahkan jika perlu rollback
        if (Schema::hasTable('teaching_activities')) {
            Schema::table('teaching_activities', function (Blueprint $table) {
                $table->dropColumn(['jam_mulai', 'jam_selesai']);
            });
        }

        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropColumn(['end_latitude', 'end_longitude']);
            });
        }
    }
}; 