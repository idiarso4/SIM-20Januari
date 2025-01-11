<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TeachingActivitiesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $activities;

    public function __construct($activities)
    {
        $this->activities = $activities;
    }

    public function collection()
    {
        return $this->activities;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kelas',
            'Mata Pelajaran',
            'Materi',
            'Jam Ke',
            'Sampai Jam Ke',
            'Jumlah Hadir',
            'Jumlah Sakit',
            'Jumlah Izin',
            'Jumlah Alpha',
            'Jumlah Dispensasi',
            'Media dan Alat',
            'Catatan Kejadian Penting'
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->tanggal->format('d/m/Y'),
            $activity->kelas->name,
            $activity->mata_pelajaran,
            $activity->materi,
            $activity->jam_ke_mulai,
            $activity->jam_ke_selesai,
            collect($activity->attendances)->where('status', 'Hadir')->count(),
            collect($activity->attendances)->where('status', 'Sakit')->count(),
            collect($activity->attendances)->where('status', 'Izin')->count(),
            collect($activity->attendances)->where('status', 'Alpha')->count(),
            collect($activity->attendances)->where('status', 'Dispensasi')->count(),
            $activity->media_dan_alat,
            $activity->important_notes
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Style for headers
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B5563'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center align specific columns
        $sheet->getStyle('E2:K' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Add borders to all cells
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 