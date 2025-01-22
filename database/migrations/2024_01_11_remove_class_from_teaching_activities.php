<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Cek keberadaan foreign key
        $foreignKeyExists = DB::select("
            SELECT * 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'teaching_activities' 
            AND CONSTRAINT_NAME = 'teaching_activities_class_room_id_foreign'
        ");

        if (!empty($foreignKeyExists)) {
            Schema::table('teaching_activities', function (Blueprint $table) {
                $table->dropForeign(['class_room_id']);
            });
        }

        // Cek keberadaan kolom
        if (Schema::hasColumn('teaching_activities', 'class_room_id')) {
            Schema::table('teaching_activities', function (Blueprint $table) {
                $table->dropColumn('class_room_id');
            });
        }
    }

    public function down()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->foreignId('kelas_id')->after('guru_id')->constrained('class_rooms');
            $table->json('attendances')->after('media_dan_alat')->nullable();
        });
    }
}; 