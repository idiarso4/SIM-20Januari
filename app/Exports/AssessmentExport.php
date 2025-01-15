<?php

namespace App\Exports;

use App\Models\Assessment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssessmentExport implements FromQuery, WithHeadings, WithMapping, WithStyles
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
            'Kelas',
            'Mata Pelajaran',
            'Kompetensi Dasar',
            'Jenis Penilaian',
            'Bobot',
            'Guru',
            'Keterangan'
        ];
    }

    public function map($record): array
    {
        return [
            $record->tanggal->format('d/m/Y'),
            $record->classRoom->name,
            $record->mata_pelajaran,
            $record->kompetensi_dasar,
            $record->jenis_penilaian,
            $record->bobot,
            $record->guru->name,
            $record->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 