<?php

namespace App\Exports;

use App\Models\ExtracurricularActivity;
use App\Models\StudentAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ExtracurricularActivitiesExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;
    protected $extracurricularId;

    public function __construct($startDate = null, $endDate = null, $extracurricularId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->extracurricularId = $extracurricularId;
    }

    public function sheets(): array
    {
        return [
            'Rekap Kegiatan' => new ExtracurricularActivitySummarySheet($this->startDate, $this->endDate, $this->extracurricularId),
            'Detail Kehadiran' => new ExtracurricularAttendanceDetailSheet($this->startDate, $this->endDate, $this->extracurricularId),
        ];
    }
}

class ExtracurricularActivitySummarySheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $extracurricularId;

    public function __construct($startDate = null, $endDate = null, $extracurricularId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->extracurricularId = $extracurricularId;
    }

    public function collection()
    {
        return ExtracurricularActivity::query()
            ->with(['extracurricular.guru', 'studentAttendances.student'])
            ->when($this->startDate, fn ($query) => $query->whereDate('tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn ($query) => $query->whereDate('tanggal', '<=', $this->endDate))
            ->when($this->extracurricularId, fn ($query) => $query->where('extracurricular_id', $this->extracurricularId))
            ->latest('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Ekstrakurikuler',
            'Pembina',
            'Jam Mulai',
            'Jam Selesai',
            'Materi',
            'Jumlah Hadir',
            'Jumlah Sakit',
            'Jumlah Izin',
            'Jumlah Alpha',
            'Jumlah Dispensasi',
        ];
    }

    public function map($activity): array
    {
        $attendances = $activity->studentAttendances;
        
        return [
            Carbon::parse($activity->tanggal)->format('d/m/Y'),
            $activity->extracurricular->nama,
            $activity->extracurricular->guru->name,
            Carbon::parse($activity->jam_mulai)->format('H:i'),
            Carbon::parse($activity->jam_selesai)->format('H:i'),
            $activity->materi,
            $attendances->where('status', 'hadir')->count(),
            $attendances->where('status', 'sakit')->count(),
            $attendances->where('status', 'izin')->count(),
            $attendances->where('status', 'alpha')->count(),
            $attendances->where('status', 'dispensasi')->count(),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set header style
        $sheet->getStyle('A1:K1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '4B5563'],
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
                'bold' => true,
            ],
        ]);

        // Set border for all cells
        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Enable auto-filter
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
}

class ExtracurricularAttendanceDetailSheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $extracurricularId;

    public function __construct($startDate = null, $endDate = null, $extracurricularId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->extracurricularId = $extracurricularId;
    }

    public function collection()
    {
        return StudentAttendance::query()
            ->join('extracurricular_activities', 'student_attendances.extracurricular_activity_id', '=', 'extracurricular_activities.id')
            ->join('extracurriculars', 'extracurricular_activities.extracurricular_id', '=', 'extracurriculars.id')
            ->join('students', 'student_attendances.student_id', '=', 'students.id')
            ->join('class_rooms', 'students.class_room_id', '=', 'class_rooms.id')
            ->select(
                'student_attendances.*',
                'extracurricular_activities.tanggal',
                'extracurriculars.nama as extracurricular_name',
                'students.nis',
                'students.nama_lengkap',
                'class_rooms.name as class_name'
            )
            ->when($this->startDate, fn ($query) => $query->whereDate('extracurricular_activities.tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn ($query) => $query->whereDate('extracurricular_activities.tanggal', '<=', $this->endDate))
            ->when($this->extracurricularId, fn ($query) => $query->where('extracurriculars.id', $this->extracurricularId))
            ->orderBy('extracurricular_activities.tanggal', 'desc')
            ->orderBy('extracurriculars.nama')
            ->orderBy('class_rooms.name')
            ->orderBy('students.nama_lengkap')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Ekstrakurikuler',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Status',
            'Keterangan',
        ];
    }

    public function map($attendance): array
    {
        return [
            Carbon::parse($attendance->tanggal)->format('d/m/Y'),
            $attendance->extracurricular_name,
            $attendance->nis,
            $attendance->nama_lengkap,
            $attendance->class_name,
            ucfirst($attendance->status),
            $attendance->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set header style
        $sheet->getStyle('A1:G1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '4B5563'],
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
                'bold' => true,
            ],
        ]);

        // Set border for all cells
        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Enable auto-filter
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
} 