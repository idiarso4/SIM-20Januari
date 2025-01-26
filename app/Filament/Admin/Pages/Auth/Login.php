<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected static ?string $navigationIcon = "heroicon-o-document-text";
    
    public static function getSlug(): string
    {
        return "login";
    }
}
