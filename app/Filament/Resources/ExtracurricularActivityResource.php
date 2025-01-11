<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtracurricularActivityResource\Pages;
use App\Models\ExtracurricularActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExtracurricularActivityResource extends Resource
{
    protected static ?string $model = ExtracurricularActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Kegiatan Ekstrakurikuler';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('guru')) {
            $query->where('guru_id', auth()->id());
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('extracurricular_id')
                    ->relationship('extracurricular', 'nama')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $extracurricular = \App\Models\Extracurricular::with('guru')->find($state);
                            if ($extracurricular && $extracurricular->guru->isNotEmpty()) {
                                $set('guru_id', auth()->id());
                            }
                        }
                    })
                    ->label('Ekstrakurikuler'),
                Forms\Components\Hidden::make('guru_id')
                    ->default(auth()->id()),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\TimePicker::make('jam_mulai')
                    ->required()
                    ->seconds(false),
                Forms\Components\TimePicker::make('jam_selesai')
                    ->required()
                    ->seconds(false),
                Forms\Components\RichEditor::make('materi')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('keterangan')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ])
                    ->required()
                    ->default('hadir'),
                Forms\Components\View::make('filament.forms.components.extracurricular-attendance-table')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guru.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('extracurricular')
                    ->relationship('extracurricular', 'nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->preload()
                    ->visible(!auth()->user()->hasRole('guru')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExtracurricularActivities::route('/'),
            'create' => Pages\CreateExtracurricularActivity::route('/create'),
            'view' => Pages\ViewExtracurricularActivity::route('/{record}'),
            'edit' => Pages\EditExtracurricularActivity::route('/{record}/edit'),
        ];
    }    
} 