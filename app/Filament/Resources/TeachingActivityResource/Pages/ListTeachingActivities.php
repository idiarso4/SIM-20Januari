<?php

namespace App\Filament\Resources\TeachingActivityResource\Pages;

use App\Filament\Resources\TeachingActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeachingActivities extends ListRecords
{
    protected static string $resource = TeachingActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kegiatan'),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'tanggal';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
} 