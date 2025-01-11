<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            SuperAdminSeeder::class,
            SubjectSeeder::class,
            DepartmentSeeder::class,
            SchoolYearSeeder::class,
            ClassRoomSeeder::class,
            ExtracurricularSeeder::class,
        ]);
    }
}
