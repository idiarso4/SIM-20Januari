<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,  // Jalankan ini dulu untuk setup roles
            TeacherSeeder::class,            // Kemudian buat users guru
            SubjectSeeder::class,
            DepartmentSeeder::class,
            SchoolYearSeeder::class,
            ClassRoomSeeder::class,
            ExtracurricularSeeder::class,
            RoleSeeder::class,
            GuruPiketSeeder::class,
        ]);
    }
}
