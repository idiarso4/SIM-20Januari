<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Models\Assessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssessmentExport;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Penilaian';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'assessments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal')
                                    ->required()
                                    ->label('Tanggal')
                                    ->rules(['required', 'date']),
                                Forms\Components\Select::make('class_room_id')
                                    ->relationship('classRoom', 'name')
                                    ->label('Kelas')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->rules(['required', 'exists:class_rooms,id']),
                                Forms\Components\TextInput::make('mata_pelajaran')
                                    ->required()
                                    ->label('Mata Pelajaran')
                                    ->rules(['required', 'string', 'max:255']),
                                Forms\Components\TextInput::make('kompetensi_dasar')
                                    ->required()
                                    ->label('Kompetensi Dasar')
                                    ->rules(['required', 'string', 'max:255']),
                                Forms\Components\TextInput::make('jenis_penilaian')
                                    ->required()
                                    ->label('Jenis Penilaian')
                                    ->rules(['required', 'string', 'max:255']),
                                Forms\Components\TextInput::make('bobot')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(100)
                                    ->required()
                                    ->label('Bobot')
                                    ->rules(['required', 'integer', 'min:1', 'max:100']),
                            ]),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Keterangan tambahan (opsional)')
                            ->columnSpanFull()
                            ->rules(['nullable', 'string']),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kompetensi_dasar')
                    ->label('Kompetensi Dasar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_penilaian')
                    ->label('Jenis Penilaian')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bobot')
                    ->label('Bobot')
                    ->sortable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),
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
                Tables\Filters\SelectFilter::make('class_room')
                    ->relationship('classRoom', 'name')
                    ->label('Kelas')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('guru')
                    ->relationship('guru', 'name')
                    ->label('Guru')
                    ->searchable()
                    ->preload()
                    ->visible(!auth()->user()->hasRole('guru')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($livewire) {
                        return Excel::download(
                            new AssessmentExport(
                                static::getEloquentQuery()
                            ),
                            'penilaian.xlsx'
                        );
                    }),
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
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('guru')) {
            $query->where('guru_id', auth()->id());
        }

        return $query;
    }
} 