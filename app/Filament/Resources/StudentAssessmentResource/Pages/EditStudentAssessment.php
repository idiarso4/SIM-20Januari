<?php

namespace App\Filament\Resources\StudentAssessmentResource\Pages;

use App\Filament\Resources\StudentAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentAssessment extends EditRecord
{
    protected static string $resource = StudentAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 