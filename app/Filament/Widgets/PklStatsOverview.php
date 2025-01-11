<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\Journal;
use App\Models\Internship;

class PklStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $user = Auth::user();
        
        if ($user->hasRole('siswa')) {
            // Stats untuk siswa
            $internship = Internship::where('user_id', $user->id)->first();
            $journals = Journal::whereHas('internship', function($q) use($user) {
                $q->where('user_id', $user->id);
            });

            return [
                Stat::make('Total Jurnal', $journals->count())
                    ->description('Semua jurnal yang dibuat')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('info'),
                    
                Stat::make('Jurnal Disetujui', $journals->where('status', 'approved')->count())
                    ->description('Jurnal yang sudah diapprove')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
                    
                Stat::make('Jurnal Pending', $journals->where('status', 'pending')->count())
                    ->description('Menunggu persetujuan')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
            ];
        }

        if ($user->hasRole('guru pembimbing')) {
            // Stats untuk pembimbing
            $totalSiswa = Internship::where('guru_pembimbing_id', $user->id)->count();
            $pendingJournals = Journal::whereHas('internship', function($q) use($user) {
                $q->where('guru_pembimbing_id', $user->id);
            })->where('status', 'pending')->count();

            return [
                Stat::make('Siswa Bimbingan', $totalSiswa)
                    ->description('Total siswa yang dibimbing')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('info'),
                    
                Stat::make('Jurnal Pending', $pendingJournals)
                    ->description('Perlu direview')
                    ->descriptionIcon('heroicon-m-clipboard-document-check')
                    ->color('warning'),
            ];
        }

        // Stats untuk admin
        return [
            Stat::make('Total Siswa PKL', Internship::count())
                ->description('Semua siswa yang sedang PKL')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
                
            Stat::make('Total Jurnal', Journal::count())
                ->description('Semua jurnal yang dibuat')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
                
            Stat::make('Jurnal Pending', Journal::where('status', 'pending')->count())
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
} 