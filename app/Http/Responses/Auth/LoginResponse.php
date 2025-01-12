<?php

namespace App\Http\Responses\Auth;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Filament\Facades\Filament;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $url = Filament::getPanel()->getUrl();
        
        if (request()->hasHeader('X-Livewire')) {
            // Jika request dari Livewire
            return redirect()->to($url);
        }
        
        // Jika request normal
        return new RedirectResponse($url);
    }
} 