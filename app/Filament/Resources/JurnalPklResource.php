<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalPklResource\Pages;
use App\Models\JurnalPkl;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class JurnalPklResource extends Resource
{
    protected static ?string $model = JurnalPkl::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Jurnal PKL';
    protected static ?string $navigationGroup = 'PKL Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form fields akan ditambahkan sesuai kebutuhan
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Table columns akan ditambahkan sesuai kebutuhan
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListJurnalPkls::route('/'),
            'create' => Pages\CreateJurnalPkl::route('/create'),
            'edit' => Pages\EditJurnalPkl::route('/{record}/edit'),
        ];
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }
} 