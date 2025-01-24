<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherJournalResource\Pages;
use App\Models\TeacherJournal;
use App\Models\TeachingActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TeacherJournalResource extends Resource
{
    protected static ?string $model = TeacherJournal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    
    protected static ?string $modelLabel = 'Jurnal Guru';
    
    protected static ?string $pluralModelLabel = 'Jurnal Guru';
    
    protected static ?string $navigationLabel = 'Jurnal Guru';
    
    protected static ?string $navigationGroup = 'Akademik';

    protected static function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal')
                ->live()
                ->dehydrated(true)
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    if ($state) {
                        $activities = TeachingActivity::where('guru_id', auth()->id())
                            ->where('tanggal', $state)
                            ->get();
                        
                        $set('teaching_activities', $activities);
                    }
                }),
            Forms\Components\Hidden::make('guru_id')
                ->default(fn () => auth()->id()),
            Forms\Components\Hidden::make('teaching_activities')
                ->default([]),
            Forms\Components\Select::make('day')
                ->label('Hari')
                ->options([
                    'Senin' => 'Senin',
                    'Selasa' => 'Selasa',
                    'Rabu' => 'Rabu',
                    'Kamis' => 'Kamis',
                    'Jumat' => 'Jumat',
                    'Sabtu' => 'Sabtu',
                ])
                ->required(),
            Forms\Components\TextInput::make('mata_pelajaran')
                ->label('Mata Pelajaran')
                ->required(),
            Forms\Components\Section::make('Kegiatan Hari Ini')
                ->schema([
                    Forms\Components\Placeholder::make('teaching_activities')
                        ->content(function ($state, $record) {
                            if (empty($state)) {
                                $activities = TeachingActivity::where('guru_id', auth()->id())
                                    ->where('tanggal', $record?->tanggal)
                                    ->get();
                            } else {
                                $activities = collect($state);
                            }

                            if ($activities->isEmpty()) {
                                return 'Tidak ada kegiatan pada tanggal ini';
                            }

                            $html = '<div class="space-y-4">';
                            foreach ($activities as $activity) {
                                $activity = is_array($activity) ? (object)$activity : $activity;
                                $html .= "<div class='p-4 bg-gray-100 rounded-lg'>";
                                $html .= "<div class='font-medium'>{$activity->mata_pelajaran}</div>";
                                $html .= "<div>Kelas: {$activity->kelas->name}</div>";
                                $html .= "<div>Jam ke: {$activity->jam_ke_mulai} - {$activity->jam_ke_selesai}</div>";
                                $html .= "<div>Materi: {$activity->materi}</div>";
                                $html .= "<div>Media: {$activity->media_dan_alat}</div>";
                                $html .= "</div>";
                            }
                            $html .= '</div>';
                            return new \Illuminate\Support\HtmlString($html);
                        })
                        ->columnSpanFull(),
                ])
                ->collapsible(),
            Forms\Components\Section::make('Refleksi Pembelajaran')
                ->schema([
                    Forms\Components\Textarea::make('kegiatan')
                        ->label('Kegiatan yang Telah Dilakukan')
                        ->required()
                        ->dehydrated(true),
                    Forms\Components\Textarea::make('hasil')
                        ->label('Hasil yang Dicapai')
                        ->required()
                        ->dehydrated(true),
                    Forms\Components\Textarea::make('hambatan')
                        ->label('Hambatan yang Ditemui')
                        ->required()
                        ->dehydrated(true),
                    Forms\Components\Textarea::make('pemecahan_masalah')
                        ->label('Pemecahan Masalah')
                        ->required()
                        ->dehydrated(true),
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan Tambahan')
                        ->dehydrated(true),
                ])
                ->columns(1),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('day')
                    ->label('Hari')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kegiatan')
                    ->label('Kegiatan')
                    ->words(10)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (str_word_count($state) > 10) {
                            return $state;
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherJournals::route('/'),
            'create' => Pages\CreateTeacherJournal::route('/create'),
            'view' => Pages\ViewTeacherJournal::route('/{record}'),
            'edit' => Pages\EditTeacherJournal::route('/{record}/edit'),
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