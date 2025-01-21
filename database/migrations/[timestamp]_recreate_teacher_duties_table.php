<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop table jika sudah ada
        Schema::dropIfExists('teacher_duties');

        Schema::create('teacher_duties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Mencegah duplikasi jadwal guru di hari yang sama
            $table->unique(['user_id', 'day']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_duties');
    }
}; 