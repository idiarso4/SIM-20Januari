<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('extracurricular_activities', function (Blueprint $table) {
            // Cek dan hapus foreign key constraints jika ada
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND TABLE_NAME = 'extracurricular_activities'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ");

            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->CONSTRAINT_NAME === 'extracurricular_activities_class_room_id_foreign') {
                    $table->dropForeign(['class_room_id']);
                }
                if ($foreignKey->CONSTRAINT_NAME === 'extracurricular_activities_guru_id_foreign') {
                    $table->dropForeign(['guru_id']);
                }
            }

            // Tambah kolom catatan jika belum ada
            if (!Schema::hasColumn('extracurricular_activities', 'catatan')) {
                $table->text('catatan')->nullable()->after('materi');
            }
            
            // Hapus kolom yang tidak digunakan lagi
            $columns = Schema::getColumnListing('extracurricular_activities');
            $columnsToRemove = [];
            
            foreach (['guru_id', 'class_room_id', 'jam_mulai', 'jam_selesai', 'keterangan', 'status'] as $column) {
                if (in_array($column, $columns)) {
                    $columnsToRemove[] = $column;
                }
            }
            
            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }

    public function down()
    {
        Schema::table('extracurricular_activities', function (Blueprint $table) {
            if (Schema::hasColumn('extracurricular_activities', 'catatan')) {
                $table->dropColumn('catatan');
            }
            
            // Kembalikan kolom lama jika perlu rollback
            if (!Schema::hasColumn('extracurricular_activities', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('extracurricular_activities', 'class_room_id')) {
                $table->foreignId('class_room_id')->nullable()->constrained();
            }
            if (!Schema::hasColumn('extracurricular_activities', 'jam_mulai')) {
                $table->time('jam_mulai')->nullable();
            }
            if (!Schema::hasColumn('extracurricular_activities', 'jam_selesai')) {
                $table->time('jam_selesai')->nullable();
            }
            if (!Schema::hasColumn('extracurricular_activities', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
            if (!Schema::hasColumn('extracurricular_activities', 'status')) {
                $table->string('status')->nullable();
            }
        });
    }
}; 