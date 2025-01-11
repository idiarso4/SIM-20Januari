<?php

namespace App\Imports;

use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\SchoolYear;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassroomImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithUpserts
{
    use SkipsErrors;

    public function uniqueBy()
    {
        return ['name', 'department_id', 'school_year_id'];
    }

    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            // Find or create department
            $department = Department::firstOrCreate(
                ['name' => $row['jurusan']],
                [
                    'code' => Str::slug($row['jurusan'], '-'),
                    'is_active' => true
                ]
            );

            // Find or create school year
            $schoolYear = SchoolYear::firstOrCreate(
                [
                    'tahun' => $row['tahun_pelajaran'],
                    'semester' => strtolower($row['semester'])
                ],
                [
                    'status' => 'aktif'
                ]
            );

            // Create new classroom instance
            $classroom = new ClassRoom([
                'name' => $row['nama_kelas'],
                'level' => $row['tingkat'],
                'department_id' => $department->id,
                'school_year_id' => $schoolYear->id,
                'is_active' => strtolower($row['status']) === 'aktif',
            ]);

            DB::commit();
            return $classroom;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
            'tahun_pelajaran' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'in:Ganjil,Genap'],
            'status' => ['required', 'string', 'in:aktif,tidak aktif'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_kelas.required' => 'Nama kelas wajib diisi',
            'tingkat.required' => 'Tingkat wajib diisi',
            'jurusan.required' => 'Jurusan wajib diisi',
            'tahun_pelajaran.required' => 'Tahun pelajaran wajib diisi',
            'semester.required' => 'Semester wajib diisi',
            'semester.in' => 'Semester harus Ganjil atau Genap',
            'status.required' => 'Status wajib diisi',
            'status.in' => 'Status harus aktif atau tidak aktif',
        ];
    }
} 