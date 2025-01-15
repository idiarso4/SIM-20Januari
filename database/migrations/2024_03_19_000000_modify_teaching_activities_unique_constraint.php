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
        
        // Buat index baru dengan nama berbeda terlebih dahulu
        DB::statement('ALTER TABLE teaching_activities ADD UNIQUE INDEX teaching_unique_new (guru_id, tanggal, jam_ke_mulai)');
        
        // Hapus index lama
        DB::statement('DROP INDEX teaching_unique ON teaching_activities');
        
        // Rename index baru ke nama yang diinginkan
        DB::statement('ALTER TABLE teaching_activities RENAME INDEX teaching_unique_new TO teaching_unique');
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Buat index lama dengan nama berbeda terlebih dahulu
        DB::statement('ALTER TABLE teaching_activities ADD UNIQUE INDEX teaching_unique_old (guru_id, tanggal)');
        
        // Hapus index baru
        DB::statement('DROP INDEX teaching_unique ON teaching_activities');
        
        // Rename index lama ke nama yang diinginkan
        DB::statement('ALTER TABLE teaching_activities RENAME INDEX teaching_unique_old TO teaching_unique');
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 