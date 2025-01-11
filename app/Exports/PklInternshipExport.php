<?php

namespace App\Exports;

use App\Models\PklInternship;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PklInternshipExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PklInternship::with(['student', 'office'])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Perusahaan',
            'Pimpinan',
            'No. Telp',
            'Mulai',
            'Selesai',
            'Posisi',
            'Status',
        ];
    }

    public function map($pkl): array
    {
        return [
            $pkl->student->name ?? '',
            $pkl->office->name ?? '',
            $pkl->company_leader,
            $pkl->company_phone,
            $pkl->start_date,
            $pkl->end_date,
            $pkl->position,
            $pkl->status,
        ];
    }
} 