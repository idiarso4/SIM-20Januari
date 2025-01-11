<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Log;

class StudentsExport implements FromCollection, WithHeadings
{
    private $records;

    public function __construct($records = null)
    {
        $this->records = $records;
    }

    public function collection()
    {
        try {
            $query = $this->records ?? Student::query();
            
            return $query
                ->select('nis', 'nama_lengkap', 'email', 'telp', 'agama', 'jenis_kelamin', 'class_room_id')
                ->with('classRoom')
                ->get()
                ->map(function ($student) {
                    return [
                        'nis' => $student->nis,
                        'nama_lengkap' => $student->nama_lengkap,
                        'email' => $student->email,
                        'telp' => $student->telp,
                        'agama' => $student->agama,
                        'jenis_kelamin' => $student->jenis_kelamin,
                        'kelas' => $student->classRoom ? $student->classRoom->name : '-'
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Collection error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Lengkap',
            'Email',
            'No. Telepon',
            'Agama',
            'Jenis Kelamin',
            'Kelas'
        ];
    }
} 