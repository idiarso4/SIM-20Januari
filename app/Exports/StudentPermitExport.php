<?php

namespace App\Exports;

use App\Models\StudentPermit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;

class StudentPermitExport implements FromCollection, WithHeadings, WithMapping
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
            'Waktu Mulai',
            'Waktu Selesai',
            'Nama Siswa',
            'Kelas',
            'Alasan',
            'Disetujui Oleh',
            'Guru Piket',
            'Status',
            'Catatan',
        ];
    }

    public function map($permit): array
    {
        return [
            $permit->id,
            $permit->permit_date->format('d/m/Y'),
            $permit->start_time->format('H:i'),
            $permit->end_time ? $permit->end_time->format('H:i') : '-',
            $permit->student->name,
            $permit->student->class_room->name ?? '-',
            $permit->reason,
            $permit->approver->name ?? '-',
            $permit->piketGuru->name ?? '-',
            match ($permit->status) {
                'pending' => 'Menunggu',
                'approved' => 'Disetujui',
                'completed' => 'Selesai',
                'rejected' => 'Ditolak',
                default => $permit->status,
            },
            $permit->notes ?? '-',
        ];
    }
} 