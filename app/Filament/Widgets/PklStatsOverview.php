<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\Journal;

class PklStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $user = Auth::user();
        
        if ($user->hasRole('guru')) {
            // Stats untuk guru
            $journals = Journal::where('guru_id', $user->id);

            return [
                Stat::make('Total Jurnal', $journals->count())
                    ->description('Semua jurnal yang dibuat')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('info'),
                    
                Stat::make('Jurnal Selesai', $journals->where('status', 'selesai')->count())
                    ->description('Jurnal yang sudah selesai')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
                    
                Stat::make('Jurnal Pending', $journals->where('status', 'pending')->count())
                    ->description('Menunggu persetujuan')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
            ];
        }

        // Stats untuk admin/superadmin
        $totalJournal = Journal::count();
        $completedJournals = Journal::where('status', 'selesai')->count();
        $pendingJournals = Journal::where('status', 'pending')->count();

        return [
            Stat::make('Total Jurnal', $totalJournal)
                ->description('Semua jurnal yang dibuat')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
                
            Stat::make('Jurnal Selesai', $completedJournals)
                ->description('Jurnal yang sudah selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Jurnal Pending', $pendingJournals)
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}