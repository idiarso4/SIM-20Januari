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
                Forms\Components\CheckboxList::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->columns(2),
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
                    ->separator(','),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('assignRoles')
                    ->label('Assign Roles')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->form([
                        Forms\Components\CheckboxList::make('roles')
                            ->label('Select Roles')
                            ->options(Role::pluck('name', 'name'))
                            ->columns(2)
                            ->required(),
                    ])
                    ->action(function (Collection $records, array $data): void {
                        foreach ($records as $user) {
                            $user->syncRoles($data['roles']);
                        }

                        Notification::make()
                            ->success()
                            ->title('Roles assigned successfully')
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\BulkAction::make('removeRoles')
                    ->label('Remove Roles')
                    ->icon('heroicon-o-user-minus')
                    ->color('danger')
                    ->form([
                        Forms\Components\CheckboxList::make('roles')
                            ->label('Select Roles to Remove')
                            ->options(Role::pluck('name', 'name'))
                            ->columns(2)
                            ->required(),
                    ])
                    ->action(function (Collection $records, array $data): void {
                        foreach ($records as $user) {
                            foreach ($data['roles'] as $role) {
                                $user->removeRole($role);
                            }
                        }

                        Notification::make()
                            ->success()
                            ->title('Roles removed successfully')
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\DeleteBulkAction::make(),
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
