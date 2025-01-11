<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAssessment extends EditRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get all students in the class with their scores
        $classStudents = $this->record->classRoom->students()
            ->orderBy('nama_lengkap')
            ->get();

        // Get existing scores with student data
        $existingScores = $this->record->studentScores()
            ->with('student')
            ->get()
            ->keyBy('student_id');

        // Prepare student scores array with all students
        $data['student_scores'] = $classStudents->map(function ($student) use ($existingScores) {
            $score = $existingScores->get($student->id);
            return [
                'student_id' => $student->id,
                'score' => $score ? $score->score : null,
                'status' => $score ? $score->status : 'hadir',
                'nama_lengkap' => $student->nama_lengkap // Add student name for display
            ];
        })->values()->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $studentScores = $data['student_scores'] ?? [];
        unset($data['student_scores']);

        $record->update($data);

        // Delete existing scores
        $record->studentScores()->delete();

        // Create new scores
        foreach ($studentScores as $score) {
            if (!empty($score['student_id'])) {
                $record->studentScores()->create([
                    'student_id' => $score['student_id'],
                    'score' => $score['status'] === 'hadir' ? $score['score'] : null,
                    'status' => $score['status'] ?? 'hadir'
                ]);
            }
        }

        // Reload the record with all relationships
        $record->load(['studentScores.student', 'classRoom']);
        return $record;
    }
} 