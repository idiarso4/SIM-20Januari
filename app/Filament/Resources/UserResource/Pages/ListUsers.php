<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('assignStudentRoles')
                ->label('Assign Student Roles')
                ->color('success')
                ->icon('heroicon-o-user-plus')
                ->action(function () {
                    // Create 'Siswa' role if it doesn't exist
                    $role = Role::firstOrCreate([
                        'name' => 'Siswa',
                        'guard_name' => 'web'
                    ]);

                    // Get all users who are marked as students
                    $users = User::where('user_type', 'siswa')
                        ->orWhere('role', 'siswa')
                        ->get();

                    $count = 0;
                    foreach ($users as $user) {
                        if (!$user->hasRole('Siswa')) {
                            $user->assignRole('Siswa');
                            $count++;
                        }
                    }

                    Notification::make()
                        ->success()
                        ->title("Role 'Siswa' assigned to {$count} users")
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
