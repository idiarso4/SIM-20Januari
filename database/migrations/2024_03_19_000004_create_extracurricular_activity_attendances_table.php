<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('extracurricular_activity_attendances')) {
            Schema::create('extracurricular_activity_attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('extracurricular_activity_id');
                $table->foreign('extracurricular_activity_id', 'extra_act_att_activity_id_foreign')
                    ->references('id')
                    ->on('extracurricular_activities')
                    ->cascadeOnDelete();
                $table->foreignId('student_id')->constrained()->cascadeOnDelete();
                $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha']);
                $table->string('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('extracurricular_activity_attendances');
    }
}; 