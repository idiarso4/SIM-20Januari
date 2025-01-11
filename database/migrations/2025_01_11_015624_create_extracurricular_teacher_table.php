<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('extracurricular_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['extracurricular_id', 'user_id']);
        });

        // Move existing guru_id data to the new pivot table
        if (Schema::hasColumn('extracurriculars', 'guru_id')) {
            $extracurriculars = DB::table('extracurriculars')
                ->whereNotNull('guru_id')
                ->get(['id', 'guru_id']);

            foreach ($extracurriculars as $extracurricular) {
                DB::table('extracurricular_teacher')->insert([
                    'extracurricular_id' => $extracurricular->id,
                    'user_id' => $extracurricular->guru_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('extracurriculars', function (Blueprint $table) {
                $table->dropForeign(['guru_id']);
                $table->dropColumn('guru_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurricular_teacher');

        if (!Schema::hasColumn('extracurriculars', 'guru_id')) {
            Schema::table('extracurriculars', function (Blueprint $table) {
                $table->foreignId('guru_id')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }
};
