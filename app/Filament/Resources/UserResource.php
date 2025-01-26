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
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = "heroicon-o-users";
    
    protected static ?string $navigationGroup = "User Management";

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make("email")
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make("password")
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === "create"),
                Forms\Components\Select::make("roles")
                    ->multiple()
                    ->relationship("roles", "name")
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->searchable(),
                Tables\Columns\TextColumn::make("email")
                    ->searchable(),
                Tables\Columns\TextColumn::make("roles.name")
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("roles")
                    ->relationship("roles", "name")
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make("assignGuruPiket")
                        ->label("Assign Guru Piket")
                        ->icon("heroicon-o-user-plus")
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            $count = 0;
                            $records->each(function ($user) use (&$count) {
                                // Only assign Guru Piket role if user already has guru role
                                if ($user->hasRole("guru")) {
                                    $user->assignRole("Guru Piket");
                                    $count++;
                                }
                            });

                            Notification::make()
                                ->success()
                                ->title("Guru Piket roles assigned")
                                ->body("{$count} teachers were assigned the Guru Piket role")
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListUsers::route("/"),
            "create" => Pages\CreateUser::route("/create"),
            "edit" => Pages\EditUser::route("/{record}/edit"),
        ];
    }
}
