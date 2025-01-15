<?php

namespace App\Filament\Resources\StudentAssessmentResource\Pages;

use App\Filament\Resources\StudentAssessmentResource;
use App\Models\Student;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditStudentAssessment extends EditRecord
{
    protected static string $resource = StudentAssessmentResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ambil data siswa berdasarkan kelas
        $students = Student::where('class_room_id', $data['class_room_id'])
            ->orderBy('nama_lengkap')
            ->get();

        // Ambil nilai yang sudah ada
        $existingDetails = $this->record->details()
            ->get()
            ->keyBy('student_id');

        // Siapkan data details
        $details = [];
        foreach ($students as $student) {
            $detail = $existingDetails->get($student->id);
            $details[] = [
                'student_id' => $student->id,
                'nama_siswa' => $student->nama_lengkap,
                'nis' => $student->nis,
                'nilai' => $detail ? $detail->nilai : null,
                'keterangan' => $detail ? $detail->keterangan : null,
            ];
        }

        $data['details'] = $details;
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Update data header
        $record->update([
            'tanggal' => $data['tanggal'],
            'class_room_id' => $data['class_room_id'],
            'mata_pelajaran' => $data['mata_pelajaran'],
            'jenis' => $data['jenis'],
            'kategori' => $data['kategori'],
            'keterangan' => $data['keterangan'],
        ]);

        // Update atau create detail nilai
        foreach ($data['details'] as $detail) {
            if (isset($detail['student_id'])) {
                $record->details()->updateOrCreate(
                    ['student_id' => $detail['student_id']],
                    [
                        'nilai' => $detail['nilai'] ?? 0,
                        'keterangan' => $detail['keterangan'] ?? null,
                    ]
                );
            }
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
