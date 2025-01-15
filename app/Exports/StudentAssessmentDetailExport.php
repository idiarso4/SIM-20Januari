<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\StudentAssessment;

class StudentAssessmentDetailExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $assessment;

    public function __construct(StudentAssessment $assessment)
    {
        $this->assessment = $assessment;
    }

    public function collection()
    {
        return $this->assessment->details()
            ->with(['student'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Siswa',
            'Nilai',
            'Keterangan',
        ];
    }

    public function map($detail): array
    {
        static $no = 1;
        return [
            $no++,
            $detail->student->nis,
            $detail->student->nama_lengkap,
            $detail->nilai,
            $detail->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set judul
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', "DAFTAR NILAI SISWA");
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Set info penilaian
        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', "Kelas: {$this->assessment->classRoom->name}");
        $sheet->mergeCells('A3:E3');
        $sheet->setCellValue('A3', "Mata Pelajaran: {$this->assessment->mata_pelajaran}");
        $sheet->mergeCells('A4:E4');
        $sheet->setCellValue('A4', "Tanggal: " . $this->assessment->tanggal->format('d/m/Y'));
        
        // Style header
        $sheet->getStyle('A5:E5')->getFont()->setBold(true);
        $sheet->getStyle('A5:E5')->getAlignment()->setHorizontal('center');
        
        // Auto size columns
        foreach(range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            5 => ['font' => ['bold' => true]],
        ];
    }
} 