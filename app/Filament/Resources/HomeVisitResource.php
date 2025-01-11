<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeVisitResource\Pages;
use App\Models\HomeVisit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\HomeVisitExport;
use Maatwebsite\Excel\Facades\Excel;

class HomeVisitResource extends Resource
{
    protected static ?string $model = HomeVisit::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $navigationLabel = 'Kunjungan Rumah';
    
    protected static ?string $modelLabel = 'Kunjungan Rumah';
    
    protected static ?string $pluralModelLabel = 'Kunjungan Rumah';
    
    protected static ?string $navigationGroup = 'Bimbingan Konseling';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('counseling_id')
                    ->relationship('counseling', 'id')
                    ->label('ID Konseling')
                    ->required(),
                    
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
                    
                Forms\Components\DatePicker::make('visit_date')
                    ->required()
                    ->label('Tanggal Kunjungan')
                    ->default(now()),
                    
                Forms\Components\TimePicker::make('visit_time')
                    ->required()
                    ->label('Waktu Kunjungan'),
                    
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->label('Alamat')
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('met_with')
                    ->required()
                    ->label('Bertemu Dengan')
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('discussion_points')
                    ->required()
                    ->label('Poin Diskusi')
                    ->rows(3),
                    
                Forms\Components\Textarea::make('agreements')
                    ->label('Kesepakatan')
                    ->rows(3),
                    
                Forms\Components\Textarea::make('recommendations')
                    ->required()
                    ->label('Rekomendasi')
                    ->rows(3),
                    
                Forms\Components\Textarea::make('follow_up_plan')
                    ->label('Rencana Tindak Lanjut')
                    ->rows(2),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'planned' => 'Direncanakan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'rescheduled' => 'Dijadwalkan Ulang',
                    ])
                    ->required()
                    ->default('planned')
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
                    
                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Disetujui Oleh')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('piketGuru.name')
                    ->label('Guru Piket')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('permit_date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('reason')
                    ->label('Alasan')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('print')
                    ->label('Cetak Surat')
                    ->icon('heroicon-o-printer')
                    ->action(function (HomeVisit $record) {
                        $pdf = Pdf::loadView('pdf.home-visit-report', [
                            'homeVisit' => $record
                        ]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'laporan-kunjungan-' . $record->id . '.pdf');
                    }),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Download Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return Excel::download(
                            new HomeVisitExport(HomeVisit::all()),
                            'kunjungan-rumah-' . now()->format('Y-m-d') . '.xlsx'
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
                                new HomeVisitExport($records),
                                'kunjungan-terpilih-' . now()->format('Y-m-d') . '.xlsx'
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
            'index' => Pages\ListHomeVisits::route('/'),
            'create' => Pages\CreateHomeVisit::route('/create'),
            'edit' => Pages\EditHomeVisit::route('/{record}/edit'),
        ];
    }    
}
