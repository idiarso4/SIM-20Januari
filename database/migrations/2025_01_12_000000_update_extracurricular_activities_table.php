<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraint first
        Schema::table('extracurricular_activity_attendances', function (Blueprint $table) {
            $table->dropForeign('extra_act_att_activity_id_foreign');
            $table->dropColumn('extracurricular_activity_id');
        });
        
        // Now we can safely drop and recreate the table
        Schema::dropIfExists('extracurricular_activities');
        
        Schema::create('extracurricular_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained('extracurriculars');
            $table->foreignId('guru_id')->constrained('users');
            $table->foreignId('class_room_id')->constrained();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('materi');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('hadir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurricular_activities');
        
        // Recreate the original foreign key
        Schema::table('extracurricular_activity_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('extracurricular_activity_id');
            $table->foreign('extracurricular_activity_id', 'extra_act_att_activity_id_foreign')
                ->references('id')
                ->on('extracurricular_activities')
                ->cascadeOnDelete();
        });
    }
};
