<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('users')->onDelete('cascade');
            $table->date('counseling_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('type'); // individu, kelompok, karir, dll
            $table->string('case_type'); // akademik, pribadi, sosial, karir
            $table->text('problem_desc');
            $table->text('solution');
            $table->text('follow_up')->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'need_visit'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselings');
    }
}; 