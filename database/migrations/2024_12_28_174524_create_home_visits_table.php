<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counseling_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('users')->onDelete('cascade');
            $table->date('visit_date');
            $table->time('visit_time');
            $table->string('address');
            $table->string('met_with'); // orangtua/wali
            $table->text('discussion_points');
            $table->text('agreements')->nullable();
            $table->text('recommendations');
            $table->text('follow_up_plan')->nullable();
            $table->enum('status', ['planned', 'completed', 'cancelled', 'rescheduled'])->default('planned');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_visits');
    }
}; 