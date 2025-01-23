<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('addGuruPiket')
                    ->label('Add Role: Guru Piket')
                    ->action(function (Collection $records) {
                        $guruPiketRole = Role::where('name', 'Guru Piket')->first();
                        
                        if (!$guruPiketRole) {
                            Notification::make()
                                ->danger()
                                ->title('Role Guru Piket tidak ditemukan')
                                ->send();
                            return;
                        }

                        $count = 0;
                        foreach ($records as $user) {
                            if (!$user->hasRole('Guru Piket')) {
                                $user->assignRole($guruPiketRole);
                                $count++;
                            }
                        }

                        Notification::make()
                            ->success()
                            ->title("Role Guru Piket berhasil ditambahkan ke {$count} pengguna")
                            ->send();
                    }),
                BulkAction::make('removeGuruPiket')
                    ->label('Remove Role: Guru Piket')
                    ->color('danger')
                    ->action(function (Collection $records) {
                        $guruPiketRole = Role::where('name', 'Guru Piket')->first();
                        
                        if (!$guruPiketRole) {
                            Notification::make()
                                ->danger()
                                ->title('Role Guru Piket tidak ditemukan')
                                ->send();
                            return;
                        }

                        $count = 0;
                        foreach ($records as $user) {
                            if ($user->hasRole('Guru Piket')) {
                                $user->removeRole($guruPiketRole);
                                $count++;
                            }
                        }

                        Notification::make()
                            ->success()
                            ->title("Role Guru Piket berhasil dihapus dari {$count} pengguna")
                            ->send();
                    }),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
