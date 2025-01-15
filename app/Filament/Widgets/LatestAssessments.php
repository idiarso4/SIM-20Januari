<?php

namespace App\Filament\Widgets;

use App\Models\StudentAssessment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAssessments extends BaseWidget
{
    protected static ?int $sort = 3;
    protected static bool $isLazy = true;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                StudentAssessment::query()
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
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge(),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (StudentAssessment $record): string => route('filament.admin.resources.student-assessments.edit', $record))
                    ->icon('heroicon-m-eye'),
            ]);
    }

    protected function getTableHeading(): string
    {
        return 'Penilaian Terbaru';
    }
} 