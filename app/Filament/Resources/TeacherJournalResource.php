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
use Illuminate\Support\HtmlString;

class TeacherJournalResource extends Resource
{
    protected static ?string $model = TeacherJournal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    
    protected static ?string $modelLabel = 'Jurnal Guru';
    
    protected static ?string $pluralModelLabel = 'Jurnal Guru';
    
    protected static ?string $navigationLabel = 'Jurnal Guru';
    
    protected static ?string $navigationGroup = 'Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal')
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $set('teaching_activities', 
                                TeachingActivity::where('guru_id', auth()->id())
                                    ->where('tanggal', $state)
                                    ->get()
                            );
                        }
                    }),
                Forms\Components\Hidden::make('guru_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\TextInput::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->required(),
                Forms\Components\Textarea::make('materi')
                    ->label('Materi')
                    ->required(),
                Forms\Components\TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->required(),
                Forms\Components\TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->required(),
                Forms\Components\Section::make('Kegiatan Hari Ini')
                    ->schema([
                        Forms\Components\Placeholder::make('teaching_activities')
                            ->content(function ($state) {
                                if (empty($state)) {
                                    return 'Pilih tanggal untuk melihat kegiatan';
                                }

                                $activities = collect($state);
                                $html = '<div class="space-y-4">';
                                foreach ($activities as $activity) {
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
                            ->placeholder('Jelaskan kegiatan pembelajaran yang telah dilakukan hari ini'),
                        Forms\Components\Textarea::make('hasil')
                            ->label('Hasil yang Dicapai')
                            ->required()
                            ->placeholder('Jelaskan hasil pembelajaran yang dicapai'),
                        Forms\Components\Textarea::make('hambatan')
                            ->label('Hambatan yang Ditemui')
                            ->required()
                            ->placeholder('Jelaskan hambatan atau kendala dalam pembelajaran'),
                        Forms\Components\Textarea::make('pemecahan_masalah')
                            ->label('Pemecahan Masalah')
                            ->required()
                            ->placeholder('Jelaskan bagaimana mengatasi hambatan tersebut'),
                    ])
                    ->columns(1),
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
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('materi')
                    ->label('Materi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->time(),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->time(),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherJournals::route('/'),
            'create' => Pages\CreateTeacherJournal::route('/create'),
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