<?php

namespace App\Filament\Resources\JadwalPiketResource\Pages;

use App\Filament\Resources\JadwalPiketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPiket extends EditRecord
{
    protected static string $resource = JadwalPiketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
