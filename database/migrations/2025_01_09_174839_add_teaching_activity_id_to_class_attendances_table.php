<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('class_attendances', function (Blueprint $table) {
            $table->foreignId('teaching_activity_id')
                ->nullable()
                ->constrained('teaching_activities')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_attendances', function (Blueprint $table) {
            $table->dropForeign(['teaching_activity_id']);
            $table->dropColumn('teaching_activity_id');
        });
    }
};
