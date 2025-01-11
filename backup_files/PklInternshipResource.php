<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PklInternshipResource\Pages;
use App\Models\PklInternship;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class PklInternshipResource extends Resource
{
    protected static ?string $model = PklInternship::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('student_id')
                ->relationship('student', 'name')
                ->required()
                ->searchable(),
                
            Select::make('guru_pembimbing_id')
                ->relationship('guruPembimbing', 'name')
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

    // ... tambahkan method lain yang diperlukan
}