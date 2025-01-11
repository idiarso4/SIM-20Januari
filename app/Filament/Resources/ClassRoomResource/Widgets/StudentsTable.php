<?php

namespace App\Filament\Resources\ClassRoomResource\Widgets;

use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class StudentsTable extends BaseWidget
{
    public $classRoomId;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Student::query()->where('class_room_id', $this->classRoomId))
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('agama')
                    ->label('Agama'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('telp')
                    ->label('No. Telepon'),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('nis')
                            ->label('NIS')
                            ->required(),
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required(),
                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        TextInput::make('agama')
                            ->label('Agama')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        TextInput::make('telp')
                            ->label('No. Telepon')
                            ->tel(),
                    ])
                    ->slideOver(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('nama_lengkap', 'asc')
            ->paginated([10, 25, 50, 100]);
    }
} 