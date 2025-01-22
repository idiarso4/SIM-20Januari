<?php

namespace App\Filament\Resources\DutyTeacherPermitResource\Pages;

use App\Filament\Resources\DutyTeacherPermitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDutyTeacherPermit extends EditRecord
{
    protected static string $resource = DutyTeacherPermitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 