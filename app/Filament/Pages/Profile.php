<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Profile extends BaseEditProfile
{
    protected static string $view = 'filament.pages.profile';
    
    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'nip' => $user->nip,
            'phone' => $user->phone,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('nip')
                    ->label('NIP')
                    ->visible(fn () => auth()->user()->hasRole('guru'))
                    ->disabled(),
                TextInput::make('phone')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('current_password')
                    ->label('Password Saat Ini')
                    ->password()
                    ->required()
                    ->dehydrated(false)
                    ->rules(['required_with:password'])
                    ->currentPassword()
                    ->autocomplete('off'),
                TextInput::make('password')
                    ->label('Password Baru')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->same('password_confirmation'),
                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->dehydrated(false),
            ])
            ->statePath('data');
    }

    protected function getFormModel(): ?\Illuminate\Database\Eloquent\Model
    {
        return Auth::user();
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();
        
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);

        if (filled($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password'])
            ]);
        }

        $this->notify('success', 'Profile updated successfully.');
    }
}