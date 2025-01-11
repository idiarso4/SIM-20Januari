<?php

namespace App\Exports;

use App\Models\ClassAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;

class ClassAttendanceExport implements FromCollection, WithHeadings, WithMapping
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
            'Jam Masuk',
            'Nama Siswa',
            'Kelas',
            'Mata Pelajaran',
            'Status',
            'Catatan',
            'Jurnal',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->id,
            $attendance->date->format('d/m/Y'),
            $attendance->check_in ? $attendance->check_in->format('H:i') : '-',
            $attendance->student->name,
            $attendance->classRoom->name ?? '-',
            $attendance->teachingActivity->subject->name ?? '-',
            match ($attendance->status) {
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'alpha' => 'Alpha',
                default => $attendance->status,
            },
            $attendance->notes ?? '-',
            $attendance->journal ?? '-',
        ];
    }
} 