<?php

namespace App\Exports;

use App\Models\Major;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MajorExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Major::select('name', 'level', 'major', 'year', 'semester', 'is_active')
            ->get()
            ->map(function ($major) {
                return [
                    'nama_kelas' => $major->name,
                    'tingkat' => $major->level,
                    'jurusan' => $major->major,
                    'tahun_pelajaran' => $major->year,
                    'semester' => $major->semester,
                    'status' => $major->is_active ? 'aktif' : 'tidak aktif'
                ];
            });
    }

    public function headings(): array
    {
        return [
            'nama_kelas',
            'tingkat',
            'jurusan',
            'tahun_pelajaran',
            'semester',
            'status'
        ];
    }
} 