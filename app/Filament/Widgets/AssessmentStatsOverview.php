<?php

namespace App\Filament\Widgets;

use App\Models\StudentAssessment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssessmentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $query = StudentAssessment::query();
        
        if (auth()->user()->hasRole('guru')) {
            $query->where('guru_id', auth()->id());
        }

        $totalPenilaian = $query->count();
        $penilaianHariIni = $query->whereDate('tanggal', today())->count();
        $rataRataNilai = $query->with('details')
            ->get()
            ->flatMap(fn ($assessment) => $assessment->details->pluck('nilai'))
            ->filter()
            ->avg();

        return [
            Stat::make('Total Penilaian', $totalPenilaian)
                ->description('Semua penilaian yang telah dibuat')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            
            Stat::make('Penilaian Hari Ini', $penilaianHariIni)
                ->description('Penilaian yang dibuat hari ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            
            Stat::make('Rata-rata Nilai', number_format($rataRataNilai ?? 0, 2))
                ->description('Rata-rata semua nilai')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
} 