<?php

namespace App\Filament\Widgets;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\TeachingActivity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClassRoomStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $query = TeachingActivity::query();
        if (auth()->user()->hasRole('guru')) {
            $query->where('guru_id', auth()->id());
        }

        $totalKelas = ClassRoom::count();
        $totalSiswa = Student::count();
        $totalPertemuan = $query->count();

        return [
            Stat::make('Total Kelas', $totalKelas)
                ->description('Jumlah kelas aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),
            
            Stat::make('Total Siswa', $totalSiswa)
                ->description('Jumlah siswa aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make('Total Pertemuan', $totalPertemuan)
                ->description('Jumlah pertemuan')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('warning'),
        ];
    }
} 