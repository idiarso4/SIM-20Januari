<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("jadwal_piket", function (Blueprint $table) {
            $table->id();
            $table->foreignId("guru_id")->constrained("users")->onDelete("cascade");
            $table->enum("hari", ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]);
            $table->time("jam_mulai");
            $table->time("jam_selesai");
            $table->boolean("is_active")->default(true);
            $table->timestamps();

            $table->unique(["guru_id", "hari"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("jadwal_piket");
    }
};
