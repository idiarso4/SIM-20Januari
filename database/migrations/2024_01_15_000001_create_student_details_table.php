<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Data Pribadi
            $table->string('nipd')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('nisn')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('religion')->nullable();
            $table->text('address')->nullable();
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('dusun')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('postal_code', 5)->nullable();
            $table->string('residence_type')->nullable();
            $table->string('transportation')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('email')->nullable();
            
            // Data Akademik
            $table->string('skhun')->nullable();
            $table->boolean('kps_recipient')->default(false);
            $table->string('kps_number')->nullable();
            $table->string('class_group')->nullable();
            $table->string('un_number')->nullable();
            $table->string('ijazah_number')->nullable();
            
            // Data KIP/KKS
            $table->boolean('kip_recipient')->default(false);
            $table->string('kip_number')->nullable();
            $table->string('kip_name')->nullable();
            $table->string('kks_number')->nullable();
            
            // Data Dokumen
            $table->string('birth_certificate_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            
            // Data PIP
            $table->boolean('pip_eligible')->default(false);
            $table->text('pip_eligible_reason')->nullable();
            $table->string('special_needs')->nullable();
            
            // Data Tambahan
            $table->string('previous_school')->nullable();
            $table->integer('child_order')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('kk_number', 16)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('head_circumference', 5, 2)->nullable();
            $table->integer('siblings_count')->nullable();
            $table->decimal('distance_to_school', 5, 2)->nullable();

            // Data Ayah
            $table->string('father_name')->nullable();
            $table->year('father_birth_year')->nullable();
            $table->string('father_education')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_income')->nullable();
            $table->string('father_nik', 16)->nullable();

            // Data Ibu
            $table->string('mother_name')->nullable();
            $table->year('mother_birth_year')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_income')->nullable();
            $table->string('mother_nik', 16)->nullable();

            // Data Wali
            $table->string('guardian_name')->nullable();
            $table->year('guardian_birth_year')->nullable();
            $table->string('guardian_education')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_income')->nullable();
            $table->string('guardian_nik', 16)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_details');
    }
}; 