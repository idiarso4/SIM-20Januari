<?php

namespace App\Filament\Resources\StudentAssessmentResource\Pages;

use App\Filament\Resources\StudentAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentAssessments extends ListRecords
{
    protected static string $resource = StudentAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
