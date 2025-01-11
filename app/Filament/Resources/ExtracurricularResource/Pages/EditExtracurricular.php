<?php

namespace App\Filament\Resources\ExtracurricularResource\Pages;

use App\Filament\Resources\ExtracurricularResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditExtracurricular extends EditRecord
{
    protected static string $resource = ExtracurricularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalDescription('Apakah Anda yakin ingin menghapus data ekstrakurikuler ini? Semua data kegiatan dan absensi terkait juga akan terhapus.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Ekstrakurikuler dihapus')
                        ->body('Data ekstrakurikuler dan semua data terkait berhasil dihapus.')
                ),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ekstrakurikuler disimpan')
            ->body('Data ekstrakurikuler berhasil diperbarui.');
    }
} 