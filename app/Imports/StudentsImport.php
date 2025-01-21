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
        try {
            Log::info('Processing row:', $row);

            $classRoom = ClassRoom::where('name', $row['kelas'])->first();
            
            if (!$classRoom) {
                Log::error('Class not found:', ['class_name' => $row['kelas']]);
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
                'email' => $row['email'] ?? null,
                'telp' => $row['no_telepon'] ?? null,
                'agama' => $row['agama'] ?? null,
                'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                'class_room_id' => $classRoom->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing row:', [
                'error' => $e->getMessage(),
                'row' => $row
            ]);
            return null;
        }
    }
} 