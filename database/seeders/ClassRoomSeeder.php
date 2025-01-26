<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::where("tahun", "2024/2025")
            ->where("semester", "genap")
            ->where("status", "aktif")
            ->first();
        
        if (!$schoolYear) {
            $schoolYear = SchoolYear::create([
                "tahun" => "2024/2025",
                "semester" => "genap",
                "status" => "aktif"
            ]);
        }
        
        $departments = [
            "akl" => Department::where("kode", "akl")->first(),
            "pplg" => Department::where("kode", "pplg")->first(),
            "to" => Department::where("kode", "to")->first(),
            "tbkr" => Department::where("kode", "tbkr")->first(),
            "tkr" => Department::where("kode", "tkr")->first(),
            "sija" => Department::where("kode", "sija")->first(),
        ];

        // Data kelas
        $classes = [
            // Kelas X AK (Akuntansi)
            [
                "name" => "X AK 1",
                "level" => "X",
                "department_id" => $departments["akl"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "X AK 2",
                "level" => "X",
                "department_id" => $departments["akl"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "X AK 3",
                "level" => "X",
                "department_id" => $departments["akl"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "X AK 4",
                "level" => "X",
                "department_id" => $departments["akl"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            // Kelas XI AKL
            [
                "name" => "XI AKL 1",
                "level" => "XI",
                "department_id" => $departments["akl"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "XI AKL 2",
                "level" => "XI",
                "department_id" => $departments["akl"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            // Kelas XII AKL
            [
                "name" => "XII AKL 1",
                "level" => "XII",
                "department_id" => $departments["akl"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "XII AKL 2",
                "level" => "XII",
                "department_id" => $departments["akl"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            
            // Kelas X PPLG (Pengembangan Perangkat Lunak dan Gim)
            [
                "name" => "X PPLG 1",
                "level" => "X",
                "department_id" => $departments["pplg"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "X PPLG 2",
                "level" => "X",
                "department_id" => $departments["pplg"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            
            // Kelas XI SIJA
            [
                "name" => "XI SIJA 1",
                "level" => "XI",
                "department_id" => $departments["sija"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "XI SIJA 2",
                "level" => "XI",
                "department_id" => $departments["sija"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "XI SIJA 3",
                "level" => "XI",
                "department_id" => $departments["sija"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            
            // Kelas XII SIJA
            [
                "name" => "XII SIJA 1",
                "level" => "XII",
                "department_id" => $departments["sija"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "XII SIJA 2",
                "level" => "XII",
                "department_id" => $departments["sija"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "XII SIJA 3",
                "level" => "XII",
                "department_id" => $departments["sija"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            
            // Kelas XIII SIJA
            [
                "name" => "XIII SIJA 1",
                "level" => "XIII",
                "department_id" => $departments["sija"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
            [
                "name" => "XIII SIJA 2",
                "level" => "XIII",
                "department_id" => $departments["sija"]->id,
                "school_year_id" => $schoolYear->id,
                "is_active" => true,
            ],
        ];

        foreach ($classes as $class) {
            ClassRoom::updateOrCreate(
                ["name" => $class["name"]],
                $class
            );
        }
    }
}
