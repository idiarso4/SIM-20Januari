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
            if (Schema::hasColumn('teaching_activities', 'jam_ke_mulai')) {
                $table->integer('jam_ke_mulai')->nullable()->change();
            } else {
                $table->integer('jam_ke_mulai')->nullable();
            }

            if (Schema::hasColumn('teaching_activities', 'jam_ke_selesai')) {
                $table->integer('jam_ke_selesai')->nullable()->change();
            } else {
                $table->integer('jam_ke_selesai')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->integer('jam_ke_mulai')->nullable(false)->change();
            $table->integer('jam_ke_selesai')->nullable(false)->change();
        });
    }
};
