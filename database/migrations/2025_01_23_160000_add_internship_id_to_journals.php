<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->foreignId('internship_id')
                ->after('id')
                ->nullable()
                ->constrained('internships')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropForeign(['internship_id']);
            $table->dropColumn('internship_id');
        });
    }
};
