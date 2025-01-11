<?php

namespace App\Exports;

use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClassRoomsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ClassRoom::with(['department', 'schoolYear', 'homeroomTeacher'])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Kelas',
            'Tingkat',
            'Jurusan',
            'Tahun Pelajaran',
            'Wali Kelas',
            'Status',
        ];
    }

    public function map($classRoom): array
    {
        return [
            $classRoom->name,
            $classRoom->level,
            $classRoom->department->name,
            $classRoom->schoolYear->tahun,
            $classRoom->homeroomTeacher?->name,
            $classRoom->is_active ? 'Ya' : 'Tidak',
        ];
    }
} 