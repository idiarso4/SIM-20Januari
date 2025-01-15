<?php

namespace App\Filament\Resources\ExtracurricularActivityResource\Pages;

use App\Filament\Resources\ExtracurricularActivityResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\ExtracurricularAttendance;
use Filament\Forms\Components\Component;

class EditExtracurricularActivity extends EditRecord
{
    protected static string $resource = ExtracurricularActivityResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['attendances'] = $this->record->attendances->map(function ($attendance) {
            return [
                'student_id' => $attendance->student_id,
                'nama_siswa' => $attendance->student->nama_lengkap,
                'nis' => $attendance->student->nis,
                'status' => $attendance->status,
                'keterangan' => $attendance->keterangan,
            ];
        })->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $attendances = collect($this->data['attendances'] ?? []);
        
        if ($attendances->isNotEmpty()) {
            // Hapus absensi yang ada
            $this->record->attendances()->delete();
            
            // Buat absensi baru
            $attendances->each(function ($attendance) {
                $this->record->attendances()->create([
                    'student_id' => $attendance['student_id'],
                    'status' => $attendance['status'],
                    'keterangan' => $attendance['keterangan'],
                ]);
            });
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 