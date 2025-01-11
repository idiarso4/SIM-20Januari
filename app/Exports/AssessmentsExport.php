<?php

namespace App\Exports;

use App\Models\Assessment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

class AssessmentsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function query()
    {
        $query = Assessment::query()
            ->with(['student', 'classRoom']);

        if ($this->ids) {
            $query->whereIn('id', $this->ids);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Siswa',
            'Kelas',
            'Jenis Penilaian',
            'Mata Pelajaran',
            'Nama Penilaian',
            'Tanggal',
            'Nilai',
            'Deskripsi',
            'Catatan',
        ];
    }

    public function map($assessment): array
    {
        return [
            $assessment->id,
            $assessment->student->name,
            $assessment->classRoom->name,
            $assessment->type,
            $assessment->subject,
            $assessment->assessment_name,
            $assessment->date->format('Y-m-d'),
            $assessment->score,
            $assessment->description,
            $assessment->notes,
        ];
    }
} 