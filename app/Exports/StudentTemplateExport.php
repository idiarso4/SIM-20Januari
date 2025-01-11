<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        // Contoh data
        return [
            [
                '12345', // nis
                'Nama Siswa 1', // nama_lengkap
                'L', // jenis_kelamin
                'Islam', // agama
                'siswa1@example.com', // email
                '08123456789', // telp
            ],
            [
                '12346',
                'Nama Siswa 2',
                'P',
                'Islam',
                'siswa2@example.com',
                '08123456790',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nis',
            'nama_lengkap',
            'jenis_kelamin',
            'agama',
            'email',
            'telp',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ],
        ];
    }
} 