<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extracurricular_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_main_supervisor')->default(false);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['extracurricular_id', 'guru_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurricular_supervisors');
    }
}; 