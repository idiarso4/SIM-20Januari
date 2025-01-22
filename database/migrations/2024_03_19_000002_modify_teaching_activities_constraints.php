<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        if (Schema::hasTable('teaching_activities')) {
            // Buat index baru dengan nama berbeda
            DB::statement('ALTER TABLE teaching_activities ADD UNIQUE INDEX teaching_unique_new (guru_id, tanggal, jam_ke)');
            
            // Hapus index lama jika ada
            $oldIndexExists = DB::select("
                SHOW INDEX FROM teaching_activities 
                WHERE Key_name = 'teaching_unique'
            ");
            
            if (!empty($oldIndexExists)) {
                DB::statement('DROP INDEX teaching_unique ON teaching_activities');
            }
            
            // Rename index baru ke nama yang diinginkan
            DB::statement('ALTER TABLE teaching_activities RENAME INDEX teaching_unique_new TO teaching_unique');
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        if (Schema::hasTable('teaching_activities')) {
            // Hapus index baru
            DB::statement('DROP INDEX teaching_unique ON teaching_activities');
            
            // Buat ulang index original
            DB::statement('ALTER TABLE teaching_activities ADD UNIQUE INDEX teaching_unique (guru_id, tanggal)');
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 