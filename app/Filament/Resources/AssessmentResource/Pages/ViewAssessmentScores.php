<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Models\Assessment;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;

class ViewAssessmentScores extends ViewRecord implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = AssessmentResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->getRecord()->studentScores()->getQuery()
            )
            ->columns([
                Tables\Columns\TextColumn::make('student.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'sakit' => 'warning',
                        'izin' => 'info',
                        'alpha' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('score')
                    ->label('Nilai')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->sortable()
                    ->placeholder('Tidak Hadir'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'sakit' => 'Sakit',
                        'izin' => 'Izin',
                        'alpha' => 'Alpha',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public function getTitle(): string
    {
        return "Nilai {$this->getRecord()->assessment_name}";
    }
} 