<?php

namespace App\Filament\Resources\StudentAssessmentResource\Pages;

use App\Filament\Resources\StudentAssessmentResource;
use Filament\Resources\Pages\Page;

class ViewStudentAssessmentScores extends Page
{
    protected static string $resource = StudentAssessmentResource::class;

    protected static string $view = 'filament.resources.student-assessment-resource.pages.view-student-assessment-scores';
} 