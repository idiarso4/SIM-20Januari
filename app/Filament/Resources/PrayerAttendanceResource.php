<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrayerAttendanceResource\Pages;
use App\Models\PrayerAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrayerAttendanceExport;
use Illuminate\Database\Eloquent\Collection;

class PrayerAttendanceResource extends Resource
{
    protected static ?string $model = PrayerAttendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    protected static ?string $navigationLabel = 'Absensi Shalat Dzuhur';
    
    protected static ?string $modelLabel = 'Absensi Shalat';

    protected static ?string $navigationGroup = 'Attendance Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->label('Tanggal'),
                Forms\Components\TimePicker::make('check_in')
                    ->label('Waktu Check-in')
                    ->seconds(false),
                Forms\Components\Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'alpha' => 'Alpha',
                    ])
                    ->required()
                    ->default('alpha'),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in')
                    ->label('Waktu Check-in')
                    ->time()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'hadir',
                        'warning' => 'izin',
                        'danger' => 'alpha',
                    ]),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'alpha' => 'Alpha',
                    ]),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Download Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn () => Excel::download(new PrayerAttendanceExport, 'absensi-shalat-' . now()->format('Y-m-d') . '.xlsx'))
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('export')
                        ->label('Download Excel Terpilih')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return Excel::download(
                                new PrayerAttendanceExport($records),
                                'absensi-shalat-terpilih-' . now()->format('Y-m-d') . '.xlsx'
                            );
                        }),
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListPrayerAttendances::route('/'),
            'create' => Pages\CreatePrayerAttendance::route('/create'),
            'edit' => Pages\EditPrayerAttendance::route('/{record}/edit'),
        ];
    }
}