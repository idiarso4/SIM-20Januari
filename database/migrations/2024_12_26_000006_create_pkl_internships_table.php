<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('pkl_internships');

        Schema::create('pkl_internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guru_pembimbing_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->string('company_leader');
            $table->string('company_type');
            $table->string('company_phone');
            $table->text('company_description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('position');
            $table->string('phone');
            $table->text('description');
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pkl_internships');
    }
}; 