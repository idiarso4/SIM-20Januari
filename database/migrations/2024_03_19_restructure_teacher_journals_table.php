<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First drop the table if it exists
        Schema::dropIfExists('teacher_journals');

        // Then create it with all required columns
        Schema::create('teacher_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->string('day');
            $table->text('notes');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_journals');
    }
}; 