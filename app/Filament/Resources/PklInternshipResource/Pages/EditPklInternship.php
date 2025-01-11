<?php

namespace App\Filament\Resources\PklInternshipResource\Pages;

use App\Filament\Resources\PklInternshipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPklInternship extends EditRecord
{
    protected static string $resource = PklInternshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 