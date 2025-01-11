<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeacherScheduleExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                'Guru Matematika',
                'X IPA 1',
                'Matematika',
                'Senin',
                '07:00',
                '08:40',
                'Ruang 101',
                'Contoh catatan',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'guru',
            'kelas',
            'mata_pelajaran',
            'hari',
            'jam_mulai',
            'jam_selesai',
            'ruangan',
            'catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 