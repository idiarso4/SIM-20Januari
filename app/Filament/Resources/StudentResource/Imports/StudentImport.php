<?php

namespace App\Filament\Resources\StudentResource\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class StudentImport implements ToCollection, WithHeadingRow
{
    public function rules(): array
    {
        return [
            'nis' => ['required', 'string', 'unique:students,nis'],
            'nama_lengkap' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'tempat_lahir' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'agama' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'class_room_id' => ['required', 'exists:class_rooms,id'],
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Create user account
            $user = User::create([
                'name' => $row['nama_lengkap'],
                'email' => $row['email'],
                'password' => Hash::make('password'), // Default password
                'user_type' => 'siswa'
            ]);

            // Create student record
            Student::create([
                'user_id' => $user->id,
                'nis' => $row['nis'],
                'nama_lengkap' => $row['nama_lengkap'],
                'email' => $row['email'],
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'agama' => $row['agama'],
                'alamat' => $row['alamat'],
                'class_room_id' => $row['class_room_id']
            ]);
        }
    }
} 