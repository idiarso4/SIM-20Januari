<?php

namespace App\Exports;

use App\Models\PrayerAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PrayerAttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PrayerAttendance::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Date',
            // ... sesuaikan dengan kolom yang ada
        ];
    }
} 