<?php

namespace App\Filament\Resources\HomeVisitResource\Pages;

use App\Filament\Resources\HomeVisitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomeVisit extends EditRecord
{
    protected static string $resource = HomeVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
