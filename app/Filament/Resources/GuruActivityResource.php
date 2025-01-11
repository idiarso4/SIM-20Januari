<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruActivityResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GuruActivityResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationLabel = 'Aktivitas Guru';
    
    protected static ?string $modelLabel = 'Aktivitas';
    
    protected static ?string $pluralModelLabel = 'Aktivitas Guru';
    
    protected static ?string $navigationGroup = 'Pembelajaran';
    
    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('guru');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profil Guru')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->disabled(),
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->disabled(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('input_absensi')
                    ->label('Input Absensi')
                    ->icon('heroicon-o-user-group')
                    ->url(fn (User $record): string => route('filament.admin.resources.teaching-activities.index')),
                Tables\Actions\Action::make('input_nilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-academic-cap')
                    ->url(fn (User $record): string => route('filament.admin.resources.assessments.index')),
                Tables\Actions\Action::make('jurnal')
                    ->label('Jurnal Mengajar')
                    ->icon('heroicon-o-book-open')
                    ->url(fn (User $record): string => route('filament.admin.resources.teaching-activities.index')),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuruActivities::route('/'),
        ];
    }
} 