<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get the foreign key name
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'student_attendances'
            AND COLUMN_NAME = 'extracurricular_activity_id'
            AND CONSTRAINT_SCHEMA = DATABASE()
        ");

        Schema::table('student_attendances', function (Blueprint $table) use ($foreignKeys) {
            if (Schema::hasColumn('student_attendances', 'extracurricular_activity_id')) {
                // Drop foreign key if exists
                if (!empty($foreignKeys)) {
                    $table->dropForeign($foreignKeys[0]->CONSTRAINT_NAME);
                }
                $table->dropColumn('extracurricular_activity_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('student_attendances', 'extracurricular_activity_id')) {
                $table->unsignedBigInteger('extracurricular_activity_id')->nullable();
                $table->foreign('extracurricular_activity_id')
                    ->references('id')
                    ->on('extracurricular_activities')
                    ->cascadeOnDelete();
            }
        });
    }
}; 