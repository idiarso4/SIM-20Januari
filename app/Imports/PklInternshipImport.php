<?php

namespace App\Imports;

use App\Models\PklInternship;
use App\Models\Student;
use App\Models\Office;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PklInternshipImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $student = Student::where('name', $row['nama_siswa'])->first();
        $office = Office::where('name', $row['perusahaan'])->first();

        return new PklInternship([
            'student_id' => $student->id ?? null,
            'office_id' => $office->id ?? null,
            'company_leader' => $row['pimpinan'],
            'company_phone' => $row['no_telp'],
            'start_date' => Carbon::parse($row['mulai'])->format('Y-m-d'),
            'end_date' => Carbon::parse($row['selesai'])->format('Y-m-d'),
            'position' => $row['posisi'],
            'status' => $row['status'],
        ]);
    }
} 