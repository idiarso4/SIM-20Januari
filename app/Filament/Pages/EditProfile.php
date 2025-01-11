<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Edit Profile';
    protected static ?string $title = 'Edit Profile';
    protected static ?string $slug = 'edit-profile';
    protected static bool $shouldRegisterNavigation = true;
    
    protected static string $view = 'filament.pages.edit-profile';
    
    public ?array $data = [];
    
    public function mount(): void 
    {
        $this->form->fill([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'nip' => auth()->user()->nip,
            'phone' => auth()->user()->phone,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nama'),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Email'),
                        TextInput::make('nip')
                            ->label('NIP'),
                        TextInput::make('phone')
                            ->label('No. Telepon'),
                        TextInput::make('current_password')
                            ->password()
                            ->label('Password Saat Ini'),
                        TextInput::make('new_password')
                            ->password()
                            ->label('Password Baru'),
                        TextInput::make('new_password_confirmation')
                            ->password()
                            ->label('Konfirmasi Password Baru'),
                    ])
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        if (filled($data['new_password'])) {
            if (! Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Password saat ini tidak sesuai')
                    ->danger()
                    ->send();
                return;
            }
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'nip' => $data['nip'],
            'phone' => $data['phone'],
        ];

        if (filled($data['new_password'])) {
            $updateData['password'] = Hash::make($data['new_password']);
        }

        $user->update($updateData);

        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->success()
            ->send();
    }
} 