<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\LoginResponse as FilamentLoginResponse;
use App\Http\Responses\Auth\LoginResponse;

class FilamentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FilamentLoginResponse::class, LoginResponse::class);
    }

    public function boot()
    {
        //
    }
} 