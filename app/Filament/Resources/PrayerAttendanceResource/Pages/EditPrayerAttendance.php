<?php

namespace App\Filament\Resources\PrayerAttendanceResource\Pages;

use App\Filament\Resources\PrayerAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrayerAttendance extends EditRecord
{
    protected static string $resource = PrayerAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
