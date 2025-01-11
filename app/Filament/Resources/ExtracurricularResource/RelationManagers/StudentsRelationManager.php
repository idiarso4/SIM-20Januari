<?php

namespace App\Filament\Resources\ExtracurricularResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';
    protected static ?string $title = 'Anggota Ekstrakurikuler';
    protected static ?string $modelLabel = 'Anggota';
    protected static ?string $pluralModelLabel = 'Anggota';
    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id')
                    ->label('Siswa')
                    ->options(
                        Student::whereDoesntHave('extracurriculars', function($query) {
                            $query->where('extracurricular_id', $this->ownerRecord->id);
                        })
                        ->whereNotNull('class_room_id')
                        ->orderBy('nama_lengkap')
                        ->pluck('nama_lengkap', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_lengkap')
            ->columns([
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('classRoom')
                    ->relationship('classRoom', 'name')
                    ->label('Kelas')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Tambah Anggota')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function ($query) {
                        return $query->whereDoesntHave('extracurriculars', function($q) {
                            $q->where('extracurricular_id', $this->ownerRecord->id);
                        })
                        ->whereNotNull('class_room_id')
                        ->orderBy('nama_lengkap');
                    })
                    ->recordSelectSearchColumns(['nama_lengkap', 'nis'])
                    ->recordTitle(fn (Model $record): string => 
                        $record->nama_lengkap . ' (' . $record->nis . ') - Kelas ' . 
                        ($record->classRoom ? $record->classRoom->name : '-'))
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->label('Hapus Terpilih'),
            ]);
    }
} 