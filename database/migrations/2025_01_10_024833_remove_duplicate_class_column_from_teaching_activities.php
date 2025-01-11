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
        Schema::table('teaching_activities', function (Blueprint $table) {
            if (Schema::hasColumn('teaching_activities', 'class_room_id')) {
                $table->dropColumn('class_room_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('teaching_activities', 'class_room_id')) {
                $table->unsignedBigInteger('class_room_id')->nullable();
                $table->foreign('class_room_id')->references('id')->on('class_rooms');
            }
        });
    }
};
