<?php

namespace App\Filament\Resources\TeachingActivityResource\Pages;

use App\Filament\Resources\TeachingActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTeachingActivity extends EditRecord
{
    protected static string $resource = TeachingActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Model $record) {
                    // Cek apakah ada absensi yang terkait
                    if ($record->attendances()->count() > 0) {
                        // Konfirmasi penghapusan data terkait
                        return Actions\DeleteAction::make()
                            ->requiresConfirmation();
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()->hasRole('guru')) {
            $data['guru_id'] = auth()->id();
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 