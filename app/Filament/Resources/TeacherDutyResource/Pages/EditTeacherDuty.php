<?php

namespace App\Filament\Resources\TeacherDutyResource\Pages;

use App\Filament\Resources\TeacherDutyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacherDuty extends EditRecord
{
    protected static string $resource = TeacherDutyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 