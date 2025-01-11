<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('guru_pembimbing_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('company_address');
            $table->string('supervisor_name');
            $table->string('supervisor_contact');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'terminated'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
}; 