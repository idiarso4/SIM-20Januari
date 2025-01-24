<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('internships')) {
            Schema::create('internships', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('students')->onDelete('cascade');
                $table->string('office');
                $table->string('jenis_perusahaan');
                $table->text('deskripsi_perusahaan');
                $table->date('tanggal_selesai');
                $table->string('telepon');
                $table->enum('status', ['aktif', 'selesai', 'berhenti'])->default('aktif');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('internships');
    }
}; 