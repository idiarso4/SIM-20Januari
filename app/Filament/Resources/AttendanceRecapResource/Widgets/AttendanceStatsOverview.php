<?php

namespace App\Filament\Resources\AttendanceRecapResource\Widgets;

use App\Models\StudentAttendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AttendanceStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $query = StudentAttendance::query();

        // Jika user adalah guru, hanya tampilkan statistik untuk kelas yang diajarnya
        if (auth()->user()->role === 'guru') {
            $query->whereHas('teachingActivity', function ($q) {
                $q->where('guru_id', auth()->id());
            });
        }

        $stats = $query->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        return [
            Stat::make('Hadir', $stats['present'] ?? 0)
                ->description('Total Kehadiran')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Izin', $stats['permit'] ?? 0)
                ->description('Total Izin')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning')
                ->chart([3, 5, 3, 4, 5, 6, 3, 5]),

            Stat::make('Sakit', $stats['sick'] ?? 0)
                ->description('Total Sakit')
                ->descriptionIcon('heroicon-m-heart')
                ->color('info')
                ->chart([3, 5, 3, 4, 5, 6, 3, 5]),

            Stat::make('Alpha', $stats['absent'] ?? 0)
                ->description('Total Alpha')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->chart([3, 5, 3, 4, 5, 6, 3, 5]),

            Stat::make('Dispensasi', $stats['dispensation'] ?? 0)
                ->description('Total Dispensasi')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('gray')
                ->chart([3, 5, 3, 4, 5, 6, 3, 5]),
        ];
    }
} 