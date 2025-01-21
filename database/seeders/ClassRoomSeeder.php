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
        $schoolYear = SchoolYear::where('tahun', '2024/2025')
            ->where('semester', 'genap')
            ->where('status', 'aktif')
            ->first();
        
        if (!$schoolYear) {
            $schoolYear = SchoolYear::create([
                'tahun' => '2024/2025',
                'semester' => 'genap',
                'status' => 'aktif'
            ]);
        }
        
        $departments = [
            'akl' => Department::where('kode', 'akl')->first(),
            'pplg' => Department::where('kode', 'pplg')->first(),
            'to' => Department::where('kode', 'to')->first(),
            'tbkr' => Department::where('kode', 'tbkr')->first(),
            'tkr' => Department::where('kode', 'tkr')->first(),
            'sija' => Department::where('kode', 'sija')->first(),
        ];

        // Data kelas
        $classes = [
            // Kelas X AK
            ['name' => 'X AK 1', 'level' => 'X', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X AK 2', 'level' => 'X', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X AK 3', 'level' => 'X', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X AK 4', 'level' => 'X', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            
            // Kelas X SIJA
            ['name' => 'X SIJA 1', 'level' => 'X', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X SIJA 2', 'level' => 'X', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X SIJA 3', 'level' => 'X', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            
            // Kelas X TO
            ['name' => 'X TO 1', 'level' => 'X', 'department_id' => $departments['to']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X TO 2', 'level' => 'X', 'department_id' => $departments['to']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X TO 3', 'level' => 'X', 'department_id' => $departments['to']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X TO 4', 'level' => 'X', 'department_id' => $departments['to']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X TO 5', 'level' => 'X', 'department_id' => $departments['to']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'X TO 6', 'level' => 'X', 'department_id' => $departments['to']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XI AKL
            ['name' => 'XI AKL 1', 'level' => 'XI', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI AKL 2', 'level' => 'XI', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI AKL 3', 'level' => 'XI', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI AKL 4', 'level' => 'XI', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XI SIJA
            ['name' => 'XI SIJA 1', 'level' => 'XI', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI SIJA 2', 'level' => 'XI', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI SIJA 3', 'level' => 'XI', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XI TBKR
            ['name' => 'XI TBKR 1', 'level' => 'XI', 'department_id' => $departments['tbkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI TBKR 2', 'level' => 'XI', 'department_id' => $departments['tbkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI TBKR 3', 'level' => 'XI', 'department_id' => $departments['tbkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XI TKR
            ['name' => 'XI TKR 1', 'level' => 'XI', 'department_id' => $departments['tkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI TKR 2', 'level' => 'XI', 'department_id' => $departments['tkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XI TKR 3', 'level' => 'XI', 'department_id' => $departments['tkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XII AKL
            ['name' => 'XII AKL 1', 'level' => 'XII', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII AKL 2', 'level' => 'XII', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII AKL 3', 'level' => 'XII', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII AKL 4', 'level' => 'XII', 'department_id' => $departments['akl']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XII SIJA
            ['name' => 'XII SIJA 1', 'level' => 'XII', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII SIJA 2', 'level' => 'XII', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII SIJA 3', 'level' => 'XII', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XII TBKR
            ['name' => 'XII TBKR 1', 'level' => 'XII', 'department_id' => $departments['tbkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII TBKR 2', 'level' => 'XII', 'department_id' => $departments['tbkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII TBKR 3', 'level' => 'XII', 'department_id' => $departments['tbkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XII TKR
            ['name' => 'XII TKR 1', 'level' => 'XII', 'department_id' => $departments['tkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII TKR 2', 'level' => 'XII', 'department_id' => $departments['tkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XII TKR 3', 'level' => 'XII', 'department_id' => $departments['tkr']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],

            // Kelas XIII SIJA
            ['name' => 'XIII SIJA 1', 'level' => 'XIII', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XIII SIJA 2', 'level' => 'XIII', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
            ['name' => 'XIII SIJA 3', 'level' => 'XIII', 'department_id' => $departments['sija']->id, 'school_year_id' => $schoolYear->id, 'is_active' => true],
        ];

        foreach ($classes as $class) {
            ClassRoom::updateOrCreate(
                ['name' => $class['name']],
                $class
            );
        }
    }
}
