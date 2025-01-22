<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('student_permits')) {
            Schema::create('student_permits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained();
                $table->foreignId('piket_guru_id')->constrained('users');
                $table->foreignId('approved_by')->nullable()->constrained('users');
                $table->text('reason');
                $table->string('status')->default('pending');
                $table->date('permit_date');
                $table->string('start_time')->nullable();
                $table->string('end_time')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('returned_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('student_permits', function (Blueprint $table) {
                if (Schema::hasColumn('student_permits', 'start_time')) {
                    $table->dropColumn('start_time');
                }
                if (Schema::hasColumn('student_permits', 'end_time')) {
                    $table->dropColumn('end_time');
                }
                $table->string('start_time')->nullable();
                $table->string('end_time')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::table('student_permits', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
        });
    }
}; 