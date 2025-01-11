<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function mount(): void
    {
        if (auth()->check()) {
            redirect()->intended(route('filament.admin.pages.dashboard'));
        }
    }
} 