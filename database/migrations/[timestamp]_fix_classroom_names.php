<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Perbaikan nama kelas X (AKL ke AK)
        DB::table('class_rooms')->where('name', 'like', 'X AKL%')->update([
            'name' => DB::raw("REPLACE(name, 'X AKL', 'X AK')")
        ]);
        
        // Perbaikan nama kelas XI dan XII (PPLG ke SIJA)
        DB::table('class_rooms')->where('name', 'like', 'XI PPLG%')->update([
            'name' => DB::raw("REPLACE(name, 'XI PPLG', 'XI SIJA')")
        ]);
        
        DB::table('class_rooms')->where('name', 'like', 'XII PPLG%')->update([
            'name' => DB::raw("REPLACE(name, 'XII PPLG', 'XII SIJA')")
        ]);
    }

    public function down()
    {
        // Rollback perubahan jika diperlukan
        DB::table('class_rooms')->where('name', 'like', 'X AK%')->update([
            'name' => DB::raw("REPLACE(name, 'X AK', 'X AKL')")
        ]);
        
        DB::table('class_rooms')->where('name', 'like', 'XI SIJA%')->update([
            'name' => DB::raw("REPLACE(name, 'XI SIJA', 'XI PPLG')")
        ]);
        
        DB::table('class_rooms')->where('name', 'like', 'XII SIJA%')->update([
            'name' => DB::raw("REPLACE(name, 'XII SIJA', 'XII PPLG')")
        ]);
    }
}; 