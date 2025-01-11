<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;

class StudentsImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        Log::info('Processing row:', $row);

        if (empty($row['nis'])) {
            Log::warning('Skipping row: NIS is empty', $row);
            return null;
        }

        $classRoom = ClassRoom::where('name', $row['kelas'])->first();
        
        if (!$classRoom) {
            Log::warning('Skipping row: Classroom not found', [
                'kelas' => $row['kelas'] ?? 'not set',
                'row' => $row
            ]);
            return null;
        }

        Log::info('Creating student:', [
            'nis' => $row['nis'],
            'nama_lengkap' => $row['nama_lengkap'],
            'class_room_id' => $classRoom->id
        ]);

        return new Student([
            'nis' => $row['nis'],
            'nama_lengkap' => $row['nama_lengkap'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'agama' => $row['agama'],
            'email' => $row['email'],
            'telp' => $row['telp'],
            'class_room_id' => $classRoom->id,
        ]);
    }
} 