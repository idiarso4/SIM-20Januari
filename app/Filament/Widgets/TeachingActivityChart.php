<?php

namespace App\Filament\Widgets;

use App\Models\TeachingActivity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TeachingActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Pembelajaran';
    protected static ?int $sort = 4;
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $data = TeachingActivity::query()
            ->when(
                auth()->user()->hasRole('guru'),
                fn($q) => $q->where('guru_id', auth()->id())
            )
            ->select(
                DB::raw('DATE(tanggal) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('tanggal', [
                now()->subDays(7)->startOfDay(),
                now()->endOfDay()
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = collect();
        $counts = collect();

        // Fill missing dates with 0
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = $data->firstWhere('date', $date)?->count ?? 0;
            
            $dates->push(now()->subDays($i)->format('d/m'));
            $counts->push($count);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Aktivitas Pembelajaran',
                    'data' => $counts->toArray(),
                    'fill' => 'start',
                    'backgroundColor' => '#fbbf2440',
                    'borderColor' => '#f59e0b',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $dates->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
} 