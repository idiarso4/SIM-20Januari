<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ClassRoom;

class ImportTemplateController extends Controller
{
    public function downloadStudentTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['nis', 'nama_lengkap', 'jenis_kelamin', 'agama', 'email', 'telp', 'kelas'];
        $examples = ['5972', 'ABDULLAH HASBIL', 'L', 'Islam', '5972@gmail.com', '81555661234', 'X TKRO 1'];
        
        // Style for headers
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B5563'],
            ],
        ];

        // Set column headers
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index); // Convert number to letter (A, B, C, etc.)
            $sheet->setCellValue($column . '1', $header);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Apply header styles
        $sheet->getStyle('A1:' . chr(64 + count($headers)) . '1')->applyFromArray($headerStyle);

        // Add example row
        foreach ($examples as $index => $example) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . '2', $example);
        }

        // Create notes sheet
        $notesSheet = $spreadsheet->createSheet();
        $notesSheet->setTitle('Petunjuk');
        $notesSheet->setCellValue('A1', 'Petunjuk Pengisian:');
        $notesSheet->setCellValue('A2', '1. NIS: Wajib diisi');
        $notesSheet->setCellValue('A3', '2. Nama Lengkap: Wajib diisi');
        $notesSheet->setCellValue('A4', '3. Jenis Kelamin: Wajib diisi (L/P)');
        $notesSheet->setCellValue('A5', '4. Agama: Wajib diisi');
        $notesSheet->setCellValue('A6', '5. Email: Wajib diisi dengan format email yang valid');
        $notesSheet->setCellValue('A7', '6. No. Telepon: Opsional');
        $notesSheet->setCellValue('A8', '7. Kelas: Wajib diisi dengan nama kelas yang tersedia');
        
        // Add available classes
        $notesSheet->setCellValue('A10', 'Daftar Kelas yang Tersedia:');
        $classes = ClassRoom::where('is_active', true)->pluck('name')->toArray();
        foreach ($classes as $index => $className) {
            $notesSheet->setCellValue('A' . ($index + 11), '- ' . $className);
        }
        
        $notesSheet->getStyle('A1')->getFont()->setBold(true);
        $notesSheet->getStyle('A10')->getFont()->setBold(true);
        $notesSheet->getColumnDimension('A')->setAutoSize(true);

        // Create writer and output
        $writer = new Xlsx($spreadsheet);
        
        $fileName = 'template_import_siswa.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function downloadGuruTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['nama_lengkap', 'email', 'nip', 'no_telepon', 'password'];
        $examples = [
            'Adelia Pratiwi',
            'adelia.tiwie@gmail.com',
            '199307312022212017',
            '089625889575',
            'password123'
        ];
        
        // Style for headers
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B5563'],
            ],
        ];

        // Set column headers
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . '1', $header);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Apply header styles
        $sheet->getStyle('A1:' . chr(64 + count($headers)) . '1')->applyFromArray($headerStyle);

        // Add example row
        foreach ($examples as $index => $example) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . '2', $example);
        }

        // Create notes sheet
        $notesSheet = $spreadsheet->createSheet();
        $notesSheet->setTitle('Petunjuk');
        $notesSheet->setCellValue('A1', 'Petunjuk Pengisian:');
        $notesSheet->setCellValue('A2', '1. Nama Lengkap: Wajib diisi');
        $notesSheet->setCellValue('A3', '2. Email: Wajib diisi dengan format email yang valid');
        $notesSheet->setCellValue('A4', '3. NIP: Wajib diisi dengan format YYYYMMDDXXXXXXXX');
        $notesSheet->setCellValue('A5', '4. No. Telepon: Wajib diisi dengan format 08XXXXXXXXXX');
        $notesSheet->setCellValue('A6', '5. Password: Wajib diisi (default: password123)');
        
        $notesSheet->getStyle('A1')->getFont()->setBold(true);
        $notesSheet->getColumnDimension('A')->setAutoSize(true);

        // Create writer and output
        $writer = new Xlsx($spreadsheet);
        
        $fileName = 'template_import_guru.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
} 