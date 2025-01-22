<?php

namespace App\Filament\Resources\DutyTeacherPermitResource\Pages;

use App\Filament\Resources\DutyTeacherPermitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDutyTeacherPermits extends ListRecords
{
    protected static string $resource = DutyTeacherPermitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
} 