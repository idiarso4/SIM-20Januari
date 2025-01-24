<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
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
            $table->dropColumn(['day', 'notes']);
        });
    }
}; 