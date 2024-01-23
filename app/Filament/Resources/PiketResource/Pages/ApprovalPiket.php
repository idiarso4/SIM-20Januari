<?php

namespace App\Filament\Resources\PiketResource\Pages;

use App\Filament\Resources\PiketResource;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use App\Models\Piket;

class ApprovalPiket extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = PiketResource::class;

    protected static string $view = 'filament.resources.piket-resource.pages.approval-piket';
    
    protected static ?string $navigationLabel = 'Persetujuan Guru Piket';
    
    protected static ?string $navigationGroup = 'Piket & Perizinan';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(Piket::where('status', 'pending'))
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(50),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(fn (Piket $record) => $record->update(['status' => 'approved'])),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->action(fn (Piket $record) => $record->update(['status' => 'rejected'])),
            ]);
    }
} 