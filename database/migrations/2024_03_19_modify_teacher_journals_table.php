<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            // Drop kolom yang tidak diperlukan
            $columnsToCheck = ['materi', 'jam_mulai', 'jam_selesai'];
            
            $columnsToDrop = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('teacher_journals', $column)) {
                    $columnsToDrop[] = $column;
                }
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            // Tambah kolom baru
            if (!Schema::hasColumn('teacher_journals', 'day')) {
                $table->string('day')->nullable()->after('guru_id');
            }
            if (!Schema::hasColumn('teacher_journals', 'notes')) {
                $table->text('notes')->nullable()->after('day');
            }
        });
    }

    public function down()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['day', 'notes']);

            // Kembalikan kolom yang dihapus
            $table->text('materi');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
        });
    }
}; 