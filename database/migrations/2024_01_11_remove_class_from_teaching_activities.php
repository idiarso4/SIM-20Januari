<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            // Attempt to drop the foreign key constraint - wrapped in try/catch in case it doesn't exist
            try {
                $table->dropForeign('teaching_activities_kelas_id_foreign');
            } catch (\Exception $e) {
                // Constraint might not exist, continue anyway
            }
            
            // Then drop the column
            $table->dropColumn('kelas_id');
        });
    }

    public function down()
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->foreignId('kelas_id')->after('guru_id')->constrained('class_rooms');
            $table->json('attendances')->after('keterangan')->nullable();
        });
    }
};
