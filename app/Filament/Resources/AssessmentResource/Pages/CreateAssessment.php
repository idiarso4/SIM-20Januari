<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAssessment extends CreateRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $studentScores = $data['student_scores'] ?? [];
        unset($data['student_scores']);

        $assessment = static::getModel()::create($data);

        // Only create scores for students that are present
        foreach ($studentScores as $score) {
            if (!empty($score['student_id'])) {
                $assessment->studentScores()->create([
                    'student_id' => $score['student_id'],
                    'score' => $score['status'] === 'hadir' ? $score['score'] : null,
                    'status' => $score['status']
                ]);
            }
        }

        return $assessment;
    }
} 