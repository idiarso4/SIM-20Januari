<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus index lama
        DB::statement('DROP INDEX teaching_unique ON teaching_activities');
        
        // Buat index baru yang mengizinkan NULL
        DB::statement('CREATE UNIQUE INDEX teaching_unique ON teaching_activities (guru_id, tanggal, jam_ke_mulai, id)');
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus index baru
        DB::statement('DROP INDEX teaching_unique ON teaching_activities');
        
        // Kembalikan ke index sebelumnya
        DB::statement('CREATE UNIQUE INDEX teaching_unique ON teaching_activities (guru_id, tanggal, jam_ke_mulai)');
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 