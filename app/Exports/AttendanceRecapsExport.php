<?php

namespace App\Exports;

use App\Models\TeachingActivity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceRecapsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return TeachingActivity::with(['guru', 'kelas', 'studentAttendances'])
            ->when(auth()->user()->role === 'guru', function ($query) {
                return $query->where('guru_id', auth()->id());
            })
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Guru',
            'Kelas',
            'Mata Pelajaran',
            'Jam Ke',
            'Sampai Jam',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpha',
            'Dispensasi',
            'Total Siswa',
            'Persentase Kehadiran'
        ];
    }

    public function map($row): array
    {
        $attendances = $row->studentAttendances->groupBy('status');
        $totalStudents = $row->kelas->students()->where('status', 'aktif')->count();
        $presentCount = $attendances->get('present', collect())->count();
        
        $percentagePresent = $totalStudents > 0 ? 
            round(($presentCount / $totalStudents) * 100, 2) : 0;

        return [
            $row->tanggal->format('d/m/Y'),
            $row->guru->name,
            $row->kelas->nama,
            $row->mata_pelajaran,
            'Jam ke-' . $row->jam_ke_mulai,
            's/d jam ke-' . $row->jam_ke_selesai,
            $presentCount,
            $attendances->get('permit', collect())->count(),
            $attendances->get('sick', collect())->count(),
            $attendances->get('absent', collect())->count(),
            $attendances->get('dispensation', collect())->count(),
            $totalStudents,
            $percentagePresent . '%'
        ];
    }
} 