<?php

namespace App\Exports;

use App\Models\TeachingActivity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;

class TeachingActivityExport implements FromCollection, WithHeadings, WithMapping
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
            'Jam Ke',
            'Jam Masuk',
            'Guru',
            'Kelas',
            'Mata Pelajaran',
            'Status',
            'Materi',
            'Catatan',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->id,
            $activity->date->format('d/m/Y'),
            $activity->jam_ke,
            $activity->check_in ? $activity->check_in->format('H:i') : '-',
            $activity->guru->name,
            $activity->classRoom->name ?? '-',
            $activity->subject->name ?? '-',
            match ($activity->status) {
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'alpha' => 'Alpha',
                default => $activity->status,
            },
            $activity->materi ?? '-',
            $activity->notes ?? '-',
        ];
    }
} 