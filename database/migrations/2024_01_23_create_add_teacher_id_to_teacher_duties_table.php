<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_duties', function (Blueprint $table) {
            // First drop the user_id foreign key if it exists
            if (Schema::hasColumn('teacher_duties', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            // Add teacher_id column if it doesn't exist
            if (!Schema::hasColumn('teacher_duties', 'teacher_id')) {
                $table->foreignId('teacher_id')
                    ->after('id')
                    ->constrained('users')
                    ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('teacher_duties', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_duties', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
        });
    }
}; 