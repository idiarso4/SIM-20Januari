<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        try {
            Log::info('Processing row:', $row);

            $classRoom = ClassRoom::where('name', $row['kelas'])->first();
            
            if (!$classRoom) {
                Log::error('Class not found:', ['class_name' => $row['kelas']]);
                return null;
            }

            // Clean and validate jenis_kelamin
            $jenisKelamin = strtoupper(trim($row['jenis_kelamin'] ?? ''));
            if (!in_array($jenisKelamin, ['L', 'P'])) {
                Log::error('Invalid jenis_kelamin:', ['value' => $jenisKelamin]);
                return null;
            }

            // Convert NIS to string if it's numeric
            $nis = is_numeric($row['nis']) ? (string)$row['nis'] : $row['nis'];

            Log::info('Creating student:', [
                'nis' => $nis,
                'nama_lengkap' => $row['nama_lengkap'],
                'class_room_id' => $classRoom->id
            ]);

            return new Student([
                'nis' => $nis,
                'nama_lengkap' => trim($row['nama_lengkap']),
                'email' => trim($row['email'] ?? ''),
                'telp' => trim($row['no_telepon'] ?? ''),
                'agama' => trim($row['agama'] ?? ''),
                'jenis_kelamin' => $jenisKelamin,
                'class_room_id' => $classRoom->id,
                'status' => 'aktif'
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing row:', [
                'error' => $e->getMessage(),
                'row' => $row
            ]);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            '*.nis' => ['required'],
            '*.nama_lengkap' => ['required', 'string'],
            '*.email' => ['required', 'email'],
            '*.no_telepon' => ['nullable'],
            '*.agama' => ['required', 'string'],
            '*.jenis_kelamin' => ['required', Rule::in(['L', 'P', 'l', 'p'])],
            '*.kelas' => ['required', 'string', 'exists:class_rooms,name'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nis.required' => 'NIS wajib diisi',
            '*.nama_lengkap.required' => 'Nama lengkap wajib diisi',
            '*.nama_lengkap.string' => 'Nama lengkap harus berupa teks',
            '*.email.required' => 'Email wajib diisi',
            '*.email.email' => 'Format email tidak valid',
            '*.agama.required' => 'Agama wajib diisi',
            '*.agama.string' => 'Agama harus berupa teks',
            '*.jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
            '*.jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            '*.kelas.required' => 'Kelas wajib diisi',
            '*.kelas.exists' => 'Kelas tidak ditemukan dalam database',
        ];
    }
}