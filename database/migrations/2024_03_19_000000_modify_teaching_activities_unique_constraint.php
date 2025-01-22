<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Tampilkan struktur tabel terlebih dahulu
        $columns = DB::select('SHOW COLUMNS FROM teaching_activities');
        $columnNames = array_column($columns, 'Field');
        
        // Tambah kolom jam_ke jika belum ada
        Schema::table('teaching_activities', function (Blueprint $table) use ($columnNames) {
            if (!in_array('jam_ke', $columnNames)) {
                $table->integer('jam_ke')->after('tanggal');
            }
        });

        // Update data dari jam_ke_mulai ke jam_ke jika ada
        if (in_array('jam_ke_mulai', $columnNames)) {
            DB::statement('UPDATE teaching_activities SET jam_ke = jam_ke_mulai');
            
            // Hapus kolom jam_ke_mulai
            Schema::table('teaching_activities', function (Blueprint $table) {
                $table->dropColumn('jam_ke_mulai');
            });
        }

        // Modifikasi index
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        if (Schema::hasTable('teaching_activities')) {
            // Buat index baru dengan nama berbeda
            DB::statement('ALTER TABLE teaching_activities ADD UNIQUE INDEX teaching_unique_new (guru_id, tanggal, jam_ke)');
            
            // Hapus index lama
            DB::statement('DROP INDEX teaching_unique ON teaching_activities');
            
            // Rename index baru ke nama yang diinginkan
            DB::statement('ALTER TABLE teaching_activities RENAME INDEX teaching_unique_new TO teaching_unique');
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // Kembalikan ke kondisi awal
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Tambah kolom jam_ke_mulai
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->integer('jam_ke_mulai')->after('tanggal');
        });

        // Pindahkan data dari jam_ke ke jam_ke_mulai
        DB::statement('UPDATE teaching_activities SET jam_ke_mulai = jam_ke');

        if (Schema::hasTable('teaching_activities')) {
            // Hapus index baru
            DB::statement('DROP INDEX teaching_unique ON teaching_activities');
            
            // Buat ulang index original
            DB::statement('ALTER TABLE teaching_activities ADD UNIQUE INDEX teaching_unique (guru_id, tanggal)');
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Hapus kolom jam_ke
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->dropColumn('jam_ke');
        });
    }
}; 