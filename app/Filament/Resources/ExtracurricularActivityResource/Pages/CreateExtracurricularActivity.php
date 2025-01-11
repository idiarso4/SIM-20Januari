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
        Notification::make()
            ->success()
            ->title('Data kegiatan ekstrakurikuler disimpan')
            ->body('Data kegiatan ekstrakurikuler berhasil disimpan.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 