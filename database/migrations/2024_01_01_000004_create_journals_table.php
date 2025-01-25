<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('jenis_kegiatan', [
                'mengajar',
                'rapat',
                'workshop',
                'pembinaan',
                'lainnya'
            ]);
            $table->string('lokasi')->nullable();
            $table->text('deskripsi');
            $table->string('bukti_kegiatan')->nullable();
            $table->enum('status', ['selesai', 'batal', 'pending'])
                ->default('selesai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index(['guru_id', 'tanggal', 'jenis_kegiatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
}; 