<?php

namespace App\Exports;

use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassroomExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return ClassRoom::select(
            'name',
            'level',
            'major_id',
            'year',
            'homeroom_teacher_id',
            'is_active'
        )->get()->map(function ($classroom) {
            return [
                'name' => $classroom->name,
                'level' => $classroom->level,
                'major' => $classroom->major->name ?? '',
                'year' => $classroom->year,
                'homeroom_teacher' => $classroom->homeroomTeacher->name ?? '',
                'is_active' => $classroom->is_active ? 'Ya' : 'Tidak',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Kelas',
            'Tingkat',
            'Jurusan',
            'Tahun',
            'Wali Kelas',
            'Status Aktif',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row
        ];
    }
} 