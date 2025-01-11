<?php

namespace App\Exports;

use App\Models\ExtracurricularAttendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;

class ExtracurricularAttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    protected $extraId;
    protected $startDate;
    protected $endDate;

    public function __construct($extraId = null, $startDate = null, $endDate = null)
    {
        $this->extraId = $extraId;
        $this->startDate = $startDate ? Carbon::parse($startDate) : null;
        $this->endDate = $endDate ? Carbon::parse($endDate) : null;
    }

    public function query()
    {
        return ExtracurricularAttendance::query()
            ->with(['user', 'extracurricular'])
            ->when($this->extraId, fn($q) => $q->where('extracurricular_id', $this->extraId))
            ->when($this->startDate, fn($q) => $q->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('date', '<=', $this->endDate))
            ->orderBy('date', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Siswa',
            'Ekstrakurikuler',
            'Waktu Check-in',
            'Status',
            'Catatan',
            'Ringkasan Kegiatan',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date->format('d/m/Y'),
            $attendance->user->name,
            $attendance->extracurricular->name,
            $attendance->check_in ? $attendance->check_in->format('H:i') : '-',
            ucfirst($attendance->status),
            $attendance->notes,
            $attendance->activity_summary,
        ];
    }
} 