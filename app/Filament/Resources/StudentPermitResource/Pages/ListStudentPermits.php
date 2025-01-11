<?php

namespace App\Filament\Resources\StudentPermitResource\Pages;

use App\Filament\Resources\StudentPermitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentPermits extends ListRecords
{
    protected static string $resource = StudentPermitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
