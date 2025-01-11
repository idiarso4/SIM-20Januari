<?php

namespace App\Imports;

use App\Models\TeacherSchedule;
use App\Models\User;
use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeacherScheduleImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $guru = User::where('name', $row['guru'])
                    ->where('role', 'guru')
                    ->first();
        $classRoom = ClassRoom::where('name', $row['kelas'])->first();

        if (!$guru || !$classRoom) {
            return null;
        }

        return new TeacherSchedule([
            'guru_id' => $guru->id,
            'class_room_id' => $classRoom->id,
            'subject' => $row['mata_pelajaran'],
            'day' => strtolower($row['hari']),
            'start_time' => $row['jam_mulai'],
            'end_time' => $row['jam_selesai'],
            'room' => $row['ruangan'] ?? null,
            'notes' => $row['catatan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'guru' => 'required',
            'kelas' => 'required',
            'mata_pelajaran' => 'required',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ];
    }
} 