<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;
use Illuminate\Http\RedirectResponse;

class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->intended(
            config('filament.home_url', FilamentManager::getUrl())
        );
    }
} 