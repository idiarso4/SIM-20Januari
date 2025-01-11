<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassroomTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            [
                'nama_kelas' => 'X SIJA 1',
                'tingkat' => 'X',
                'jurusan' => 'Sistem Informasi Jaringan dan Aplikasi',
                'tahun_pelajaran' => '2024/2025',
                'semester' => 'Ganjil',
                'status' => 'aktif'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Kelas',
            'Tingkat',
            'Jurusan',
            'Tahun Pelajaran',
            'Semester',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['italic' => true]], // Example row
        ];
    }
} 