<?php

namespace App\Filament\Widgets;

use App\Models\TeachingActivity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivitiesWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected static bool $isLazy = true;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TeachingActivity::query()
                    ->when(
                        auth()->user()->hasRole('guru'),
                        fn($q) => $q->where('guru_id', auth()->id())
                    )
                    ->latest('tanggal')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('materi')
                    ->label('Materi')
                    ->limit(30),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru')
                    ->searchable(),
            ]);
    }

    protected function getTableHeading(): string
    {
        return 'Aktivitas Pembelajaran Terbaru';
    }
} 