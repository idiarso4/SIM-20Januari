<?php

namespace App\Filament\Resources\GuruActivityResource\Pages;

use App\Filament\Resources\GuruActivityResource;
use Filament\Resources\Pages\ListRecords;

class ListGuruActivities extends ListRecords
{
    protected static string $resource = GuruActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
} 