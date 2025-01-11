<?php

namespace App\Exports;

use App\Models\HomeVisit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;

class HomeVisitExport implements FromCollection, WithHeadings, WithMapping
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
            'Waktu',
            'Nama Siswa',
            'Kelas',
            'Guru BK',
            'Alamat',
            'Bertemu Dengan',
            'Poin Diskusi',
            'Kesepakatan',
            'Rekomendasi',
            'Rencana Tindak Lanjut',
            'Status',
            'Catatan',
        ];
    }

    public function map($homeVisit): array
    {
        return [
            $homeVisit->id,
            $homeVisit->visit_date->format('d/m/Y'),
            $homeVisit->visit_time->format('H:i'),
            $homeVisit->student->name,
            $homeVisit->student->class_room->name ?? '-',
            $homeVisit->counselor->name,
            $homeVisit->address,
            $homeVisit->met_with,
            $homeVisit->discussion_points,
            $homeVisit->agreements ?? '-',
            $homeVisit->recommendations,
            $homeVisit->follow_up_plan ?? '-',
            match ($homeVisit->status) {
                'pending' => 'Menunggu',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'rescheduled' => 'Dijadwalkan Ulang',
                default => $homeVisit->status,
            },
            $homeVisit->notes ?? '-',
        ];
    }
} 