<?php

namespace App\Filament\Resources\ExtracurricularActivityResource\Pages;

use App\Filament\Resources\ExtracurricularActivityResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateExtracurricularActivity extends CreateRecord
{
    protected static string $resource = ExtracurricularActivityResource::class;

    protected function afterCreate(): void
    {
        $attendances = collect($this->data['attendances'] ?? []);
        
        if ($attendances->isNotEmpty()) {
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['guru_id'] = auth()->id();
        return $data;
    }
} 