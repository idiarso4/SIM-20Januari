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

    protected function afterSave(): void
    {
        $attendanceData = $this->data['attendance_data'] ?? [];
        
        foreach ($attendanceData as $data) {
            ExtracurricularAttendance::updateOrCreate(
                [
                    'extracurricular_activity_id' => $this->record->id,
                    'student_id' => $data['student_id'],
                ],
                [
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'],
                ]
            );
        }

        Notification::make()
            ->success()
            ->title('Data kegiatan ekstrakurikuler diperbarui')
            ->body('Data kegiatan ekstrakurikuler berhasil diperbarui.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 