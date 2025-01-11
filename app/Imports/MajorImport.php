<?php

namespace App\Imports;

use App\Models\Major;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MajorImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Major([
            'name' => $row['nama_kelas'],
            'level' => $row['tingkat'],
            'major' => $row['jurusan'],
            'year' => $row['tahun_pelajaran'],
            'semester' => $row['semester'],
            'is_active' => strtolower($row['status']) === 'aktif'
        ]);
    }
} 