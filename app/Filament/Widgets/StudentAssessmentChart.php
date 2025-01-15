<?php

namespace App\Filament\Widgets;

use App\Models\StudentAssessment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StudentAssessmentChart extends ChartWidget
{
    protected static ?string $heading = 'Rata-rata Nilai per Kategori';
    protected static ?int $sort = 5;
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $data = StudentAssessment::query()
            ->when(
                auth()->user()->hasRole('guru'),
                fn($q) => $q->where('guru_id', auth()->id())
            )
            ->join('student_assessment_details', 'student_assessments.id', '=', 'student_assessment_details.student_assessment_id')
            ->select('kategori', DB::raw('AVG(student_assessment_details.nilai) as rata_rata'))
            ->groupBy('kategori')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata Nilai',
                    'data' => $data->pluck('rata_rata'),
                    'backgroundColor' => ['#fbbf24', '#60a5fa'],
                ],
            ],
            'labels' => $data->pluck('kategori'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
} 