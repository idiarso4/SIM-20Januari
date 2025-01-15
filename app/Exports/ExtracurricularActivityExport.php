<?php

namespace App\Exports;

use App\Models\ExtracurricularActivity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExtracurricularActivityExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Ekstrakurikuler',
            'Materi',
            'Pembina',
            'Hadir',
            'Sakit',
            'Izin',
            'Alpha',
        ];
    }

    public function map($record): array
    {
        $summary = [
            'Hadir' => 0,
            'Sakit' => 0,
            'Izin' => 0,
            'Alpha' => 0
        ];
        
        foreach ($record->attendances as $attendance) {
            $summary[$attendance->status]++;
        }

        return [
            $record->tanggal->format('d/m/Y'),
            $record->extracurricular->nama,
            $record->materi,
            $record->guru->name,
            $summary['Hadir'],
            $summary['Sakit'],
            $summary['Izin'],
            $summary['Alpha'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 