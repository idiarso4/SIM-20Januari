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
            $table->json('attendances')->nullable()->after('media_dan_alat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_activities', function (Blueprint $table) {
            $table->dropColumn('attendances');
        });
    }
};