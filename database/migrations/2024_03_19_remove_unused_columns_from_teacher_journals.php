<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            // Drop kolom yang tidak diperlukan jika ada
            $columnsToCheck = ['materi', 'jam_mulai', 'jam_selesai'];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('teacher_journals', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            if (!Schema::hasColumn('teacher_journals', 'materi')) {
                $table->text('materi');
            }
            if (!Schema::hasColumn('teacher_journals', 'jam_mulai')) {
                $table->time('jam_mulai');
            }
            if (!Schema::hasColumn('teacher_journals', 'jam_selesai')) {
                $table->time('jam_selesai');
            }
        });
    }
}; 