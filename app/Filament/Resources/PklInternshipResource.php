<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PklInternshipResource\Pages;
use App\Models\PklInternship;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Models\User;

class PklInternshipResource extends Resource
{
    protected static ?string $model = PklInternship::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    
    protected static ?string $slug = 'internships';

    protected static ?string $navigationGroup = 'PKL Management';

    protected static ?string $modelLabel = 'Data PKL';
    protected static ?string $pluralModelLabel = 'Data PKL';

    protected static ?string $navigationLabel = 'Data PKL';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->label('Siswa')
                    ->options(User::where('role', 'siswa')->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                    
                Select::make('guru_pembimbing_id')
                    ->relationship('guruPembimbing', 'name')
                    ->label('Guru Pembimbing')
                    ->options(User::where('role', 'guru pembimbing')->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                    
                Select::make('office_id')
                    ->relationship('office', 'name')
                    ->required()
                    ->searchable(),
                    
                TextInput::make('company_leader')
                    ->label('Pimpinan Perusahaan')
                    ->required(),
                    
                TextInput::make('company_type')
                    ->label('Jenis Perusahaan')
                    ->required(),
                    
                TextInput::make('company_phone')
                    ->label('Telepon Perusahaan')
                    ->tel()
                    ->required(),
                    
                Textarea::make('company_description')
                    ->label('Deskripsi Perusahaan')
                    ->required(),
                    
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                    
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required(),
                    
                TextInput::make('position')
                    ->label('Posisi')
                    ->required(),
                    
                TextInput::make('phone')
                    ->label('Telepon')
                    ->tel()
                    ->required(),
                    
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required(),
                    
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('office.name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company_leader')
                    ->label('Pimpinan')
                    ->searchable(),
                TextColumn::make('company_phone')
                    ->label('No. Telp'),
                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Posisi'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'warning',
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPklInternships::route('/'),
            'create' => Pages\CreatePklInternship::route('/create'),
            'edit' => Pages\EditPklInternship::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'PKL Management';
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }
}