<?php

namespace App\Exports;

use App\Models\Counseling;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;

class CounselingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records instanceof Collection ? $records : collect([$records]);
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Nama Siswa',
            'Kelas',
            'Guru BK',
            'Jenis Konseling',
            'Permasalahan',
            'Penyelesaian',
            'Tindak Lanjut',
            'Status',
            'Catatan',
        ];
    }

    public function map($counseling): array
    {
        return [
            $counseling->id,
            $counseling->counseling_date->format('d/m/Y'),
            $counseling->student->name,
            $counseling->student->class_room->name ?? '-',
            $counseling->counselor->name,
            $counseling->counseling_type,
            $counseling->problem_description,
            $counseling->resolution,
            $counseling->follow_up ?? '-',
            match ($counseling->status) {
                'pending' => 'Menunggu',
                'in_progress' => 'Sedang Berlangsung',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                default => $counseling->status,
            },
            $counseling->notes ?? '-',
        ];
    }
} 