<?php

namespace App\Filament\Resources\TeacherJournalResource\Pages;

use App\Filament\Resources\TeacherJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacherJournal extends EditRecord
{
    protected static string $resource = TeacherJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 