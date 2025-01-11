<?php

namespace App\Filament\Resources\HomeVisitResource\Pages;

use App\Filament\Resources\HomeVisitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomeVisits extends ListRecords
{
    protected static string $resource = HomeVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
