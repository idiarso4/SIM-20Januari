<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    'maintenance' => [
        'driver' => 'file',
    ],
    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        Maatwebsite\Excel\ExcelServiceProvider::class,
        BezhanSalleh\FilamentShield\FilamentShieldServiceProvider::class,
        Filament\FilamentServiceProvider::class,
        Filament\Forms\FormsServiceProvider::class,
        Filament\Tables\TablesServiceProvider::class,
        Filament\Notifications\NotificationsServiceProvider::class,
        Filament\Support\SupportServiceProvider::class,
        Filament\Actions\ActionsServiceProvider::class,
        Filament\Infolists\InfolistsServiceProvider::class,
        Filament\Widgets\WidgetsServiceProvider::class,
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
        Spatie\Backup\BackupServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\Filament\AdminPanelProvider::class,
        App\Providers\FilamentLoginServiceProvider::class,
        App\Providers\FortifyServiceProvider::class,
        App\Providers\FilamentServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
        'PDF' => Barryvdh\DomPDF\Facade\Pdf::class,
    ])->toArray(),
];