<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GuruTemplateExport implements FromArray, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            [
                'nama_lengkap',
                'email',
                'nip',
                'no_telepon',
                'password',
            ],
            [
                'John Doe',
                'john@example.com',
                '198501012015011001',
                '081234567890',
                'password123',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B5563'],
            ],
        ]);

        // Format SELURUH kolom NIP sebagai text
        $sheet->getStyle('C:C')->getNumberFormat()->setFormatCode('@');

        // Tambahkan catatan
        $sheet->setCellValue('A4', 'Catatan:');
        $sheet->setCellValue('A5', '* Kolom nama_lengkap dan email wajib diisi');
        $sheet->setCellValue('A6', '* Kolom NIP sudah diformat sebagai teks, langsung ketik tanpa tanda petik');
        $sheet->setCellValue('A7', '* Password default: password123 (jika tidak diisi)');

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'E' => 15,
        ];
    }
}
