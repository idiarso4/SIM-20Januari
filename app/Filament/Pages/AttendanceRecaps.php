<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use App\Models\ClassRoom;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\TeachingActivity;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeachingActivityExport;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;

class AttendanceRecaps extends Page implements HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Rekap Presensi';
    protected static ?string $title = 'Rekap Presensi';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.attendance-recaps';
    protected static ?string $slug = 'attendance-recaps';

    public $class_room_id;
    public $date;
    public $summary;

    public function mount(): void
    {
        $this->form->fill();
        $this->loadSummary();
    }

    protected function loadSummary(): void
    {
        $query = TeachingActivity::query()
            ->when($this->class_room_id, fn ($q) => 
                $q->where('class_room_id', $this->class_room_id)
            )
            ->when($this->date, fn ($q) => 
                $q->whereDate('date', $this->date)
            );

        $this->summary = [
            'total' => $query->count(),
            'hadir' => $query->where('status', 'hadir')->count(),
            'izin' => $query->where('status', 'izin')->count(),
            'sakit' => $query->where('status', 'sakit')->count(),
            'alpha' => $query->where('status', 'alpha')->count(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('class_room_id')
                    ->label('Pilih Kelas')
                    ->options(ClassRoom::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn () => $this->loadSummary()),

                DatePicker::make('date')
                    ->label('Tanggal')
                    ->default(now())
                    ->live()
                    ->afterStateUpdated(fn () => $this->loadSummary()),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
                return TeachingActivity::query()
                    ->when($this->class_room_id, fn ($q) => 
                        $q->where('class_room_id', $this->class_room_id)
                    )
                    ->when($this->date, fn ($q) => 
                        $q->whereDate('date', $this->date)
                    );
            })
            ->columns([
                TextColumn::make('guru.name')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('jam_ke')
                    ->label('Jam Ke')
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label('Jam Masuk')
                    ->dateTime('H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        'alpha' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                        default => $state,
                    }),
                TextColumn::make('materi')
                    ->label('Materi')
                    ->limit(30)
                    ->searchable(),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Download Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        $records = TeachingActivity::query()
                            ->when($this->class_room_id, fn ($q) => 
                                $q->where('class_room_id', $this->class_room_id)
                            )
                            ->when($this->date, fn ($q) => 
                                $q->whereDate('date', $this->date)
                            )
                            ->get();

                        return Excel::download(
                            new TeachingActivityExport($records),
                            'rekap-absensi-' . now()->format('Y-m-d') . '.xlsx'
                        );
                    })
            ])
            ->filters([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
} 