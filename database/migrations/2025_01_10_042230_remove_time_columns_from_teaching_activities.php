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
        // Cek apakah kolom ada sebelum mencoba menghapusnya
        $columns = DB::select("SHOW COLUMNS FROM teaching_activities");
        $columnNames = array_map(function($column) {
            return $column->Field;
        }, $columns);

        Schema::table('teaching_activities', function (Blueprint $table) use ($columnNames) {
            if (in_array('jam_mulai', $columnNames)) {
                $table->dropColumn('jam_mulai');
            }
            if (in_array('jam_selesai', $columnNames)) {
                $table->dropColumn('jam_selesai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('keterangan');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });
    }
};
