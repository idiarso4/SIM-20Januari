<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_assessments', function (Blueprint $table) {
            // Cek apakah foreign key ada dengan query langsung
            $constraintExists = DB::select("
                SELECT COUNT(*) as count
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND TABLE_NAME = 'student_assessments' 
                AND CONSTRAINT_NAME = 'student_assessments_teacher_journal_id_foreign'
            ")[0]->count > 0;

            if ($constraintExists) {
                $table->dropForeign(['teacher_journal_id']);
            }

            if (Schema::hasColumn('student_assessments', 'teacher_journal_id')) {
                $table->dropColumn('teacher_journal_id');
            }
        });
    }

    public function down()
    {
        Schema::table('student_assessments', function (Blueprint $table) {
            if (!Schema::hasColumn('student_assessments', 'teacher_journal_id')) {
                $table->foreignId('teacher_journal_id')->nullable()->constrained();
            }
        });
    }
}; 