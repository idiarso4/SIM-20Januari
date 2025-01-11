<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('pencapaian');
            $table->text('kendala');
            $table->text('solusi');
            $table->text('rencana_tindak_lanjut');
            $table->timestamps();
            
            $table->unique(['guru_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_journals');
    }
}; 