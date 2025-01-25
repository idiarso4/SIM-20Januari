<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('location');
            $table->text('damage_description');
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->date('reported_date');
            $table->string('reported_by');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
}; 