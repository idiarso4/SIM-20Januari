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

        // Cari foreign key yang menggunakan index teaching_unique
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'teaching_activities'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        // Hapus foreign keys yang ditemukan
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE teaching_activities DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // Cek dan update index
        $indexExists = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'teaching_activities'
            AND INDEX_NAME = 'teaching_unique'
        ")[0]->count > 0;

        if ($indexExists) {
            // Buat index baru dengan nama berbeda
            DB::statement('CREATE UNIQUE INDEX teaching_unique_new ON teaching_activities (guru_id, tanggal, jam_ke_mulai)');
            // Hapus index lama
            DB::statement('DROP INDEX teaching_unique ON teaching_activities');
            // Rename index baru ke nama yang diinginkan
            DB::statement('ALTER TABLE teaching_activities RENAME INDEX teaching_unique_new TO teaching_unique');
        }

        // Kembalikan foreign keys
        foreach ($foreignKeys as $fk) {
            DB::statement("
                ALTER TABLE teaching_activities 
                ADD CONSTRAINT `{$fk->CONSTRAINT_NAME}` 
                FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE CASCADE
            ");
        }

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // Sama seperti sebelumnya, tapi dengan urutan terbalik
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Hapus foreign keys
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'teaching_activities'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE teaching_activities DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // Update index
        $indexExists = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'teaching_activities'
            AND INDEX_NAME = 'teaching_unique'
        ")[0]->count > 0;

        if ($indexExists) {
            DB::statement('CREATE UNIQUE INDEX teaching_unique_old ON teaching_activities (guru_id, tanggal)');
            DB::statement('DROP INDEX teaching_unique ON teaching_activities');
            DB::statement('ALTER TABLE teaching_activities RENAME INDEX teaching_unique_old TO teaching_unique');
        }

        // Kembalikan foreign keys
        foreach ($foreignKeys as $fk) {
            DB::statement("
                ALTER TABLE teaching_activities 
                ADD CONSTRAINT `{$fk->CONSTRAINT_NAME}` 
                FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE CASCADE
            ");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 