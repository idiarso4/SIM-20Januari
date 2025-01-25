<?php

namespace Database\Seeders;

use App\Models\ClassAttendance;
use Illuminate\Database\Seeder;

class ClassAttendanceSeeder extends Seeder
{
    public function run()
    {
        ClassAttendance::factory()->count(50)->create();
    }
} 