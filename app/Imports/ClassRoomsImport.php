<?php

namespace App\Imports;

use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\SchoolYear;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class ClassRoomsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_kelas'])) {
            Log::warning('Skipping row: Empty class name');
            return null;
        }

        $department = Department::where('name', $row['jurusan'])->first();
        if (!$department) {
            Log::warning('Skipping row: Department not found', ['jurusan' => $row['jurusan']]);
            return null;
        }

        $schoolYear = SchoolYear::where('tahun', $row['tahun_pelajaran'])->first();
        if (!$schoolYear) {
            Log::warning('Skipping row: School year not found', ['tahun' => $row['tahun_pelajaran']]);
            return null;
        }

        $homeroomTeacher = null;
        if (!empty($row['wali_kelas'])) {
            $homeroomTeacher = User::role('guru')->where('name', $row['wali_kelas'])->first();
            if (!$homeroomTeacher) {
                Log::warning('Skipping row: Homeroom teacher not found', ['guru' => $row['wali_kelas']]);
                return null;
            }
        }

        return new ClassRoom([
            'name' => $row['nama_kelas'],
            'level' => $row['tingkat'],
            'department_id' => $department->id,
            'school_year_id' => $schoolYear->id,
            'homeroom_teacher_id' => $homeroomTeacher?->id,
            'is_active' => strtolower($row['status'] ?? 'ya') === 'ya',
        ]);
    }
} 