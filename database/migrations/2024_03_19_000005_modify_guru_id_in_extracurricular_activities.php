<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('extracurricular_activities', function (Blueprint $table) {
            // Tambah kolom guru_id jika belum ada
            if (!Schema::hasColumn('extracurricular_activities', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->constrained('users');
            } else {
                // Jika sudah ada, ubah menjadi nullable
                $table->foreignId('guru_id')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('extracurricular_activities', function (Blueprint $table) {
            // Kembalikan ke required jika kolom ada
            if (Schema::hasColumn('extracurricular_activities', 'guru_id')) {
                $table->foreignId('guru_id')->nullable(false)->change();
            }
        });
    }
}; 