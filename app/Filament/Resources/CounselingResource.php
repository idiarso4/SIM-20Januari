<?php

namespace App\Filament\Resources;

use App\Exports\CounselingExport;
use App\Filament\Resources\CounselingResource\Pages;
use App\Models\Counseling;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class CounselingResource extends Resource
{
    protected static ?string $model = Counseling::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationLabel = 'Bimbingan Konseling';
    
    protected static ?string $modelLabel = 'Konseling Siswa';
    
    protected static ?string $pluralModelLabel = 'Konseling Siswa';
    
    protected static ?string $navigationGroup = 'Bimbingan Konseling';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required()
                    ->label('Nama Siswa')
                    ->searchable(),
                    
                Forms\Components\Select::make('counselor_id')
                    ->relationship('counselor', 'name')
                    ->label('Guru BK')
                    ->required()
                    ->searchable(),
                    
                Forms\Components\DatePicker::make('counseling_date')
                    ->required()
                    ->label('Tanggal Konseling')
                    ->default(now()),
                    
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->label('Waktu Mulai'),
                    
                Forms\Components\TimePicker::make('end_time')
                    ->label('Waktu Selesai'),
                    
                Forms\Components\Select::make('type')
                    ->options([
                        'individu' => 'Konseling Individu',
                        'kelompok' => 'Konseling Kelompok',
                        'karir' => 'Bimbingan Karir',
                        'akademik' => 'Bimbingan Akademik',
                    ])
                    ->required()
                    ->label('Jenis Konseling'),
                    
                Forms\Components\Select::make('case_type')
                    ->options([
                        'akademik' => 'Masalah Akademik',
                        'pribadi' => 'Masalah Pribadi',
                        'sosial' => 'Masalah Sosial',
                        'karir' => 'Masalah Karir',
                    ])
                    ->required()
                    ->label('Jenis Masalah'),
                    
                Forms\Components\Textarea::make('problem_desc')
                    ->required()
                    ->label('Deskripsi Masalah')
                    ->rows(3),
                    
                Forms\Components\Textarea::make('solution')
                    ->required()
                    ->label('Solusi/Penanganan')
                    ->rows(3),
                    
                Forms\Components\Textarea::make('follow_up')
                    ->label('Tindak Lanjut')
                    ->rows(2),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Baru',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        'need_visit' => 'Perlu Kunjungan',
                    ])
                    ->required()
                    ->default('open')
                    ->label('Status'),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan Tambahan')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('counselor.name')
                    ->label('Guru BK')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('counseling_date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'individu' => 'Konseling Individu',
                        'kelompok' => 'Konseling Kelompok',
                        'karir' => 'Bimbingan Karir',
                        'akademik' => 'Bimbingan Akademik',
                    }),
                    
                Tables\Columns\TextColumn::make('case_type')
                    ->label('Masalah')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'akademik' => 'Akademik',
                        'pribadi' => 'Pribadi',
                        'sosial' => 'Sosial',
                        'karir' => 'Karir',
                    }),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'need_visit' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Baru',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        'need_visit' => 'Perlu Kunjungan',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'individu' => 'Konseling Individu',
                        'kelompok' => 'Konseling Kelompok',
                        'karir' => 'Bimbingan Karir',
                        'akademik' => 'Bimbingan Akademik',
                    ])
                    ->label('Jenis Konseling'),
                    
                Tables\Filters\SelectFilter::make('case_type')
                    ->options([
                        'akademik' => 'Masalah Akademik',
                        'pribadi' => 'Masalah Pribadi',
                        'sosial' => 'Masalah Sosial',
                        'karir' => 'Masalah Karir',
                    ])
                    ->label('Jenis Masalah'),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Baru',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        'need_visit' => 'Perlu Kunjungan',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Download Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return Excel::download(
                            new CounselingExport(Counseling::all()),
                            'konseling-siswa-' . now()->format('Y-m-d') . '.xlsx'
                        );
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('export')
                        ->label('Download Excel Terpilih')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return Excel::download(
                                new CounselingExport($records),
                                'konseling-terpilih-' . now()->format('Y-m-d') . '.xlsx'
                            );
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCounselings::route('/'),
            'create' => Pages\CreateCounseling::route('/create'),
            'edit' => Pages\EditCounseling::route('/{record}/edit'),
        ];
    }    
}
