<?php

return [
    'path' => env('FILAMENT_PATH', 'admin'),
    'domain' => env('FILAMENT_DOMAIN'),
    'home_url' => env('FILAMENT_HOME_URL', '/'),
    'brand' => env('FILAMENT_BRAND_NAME', 'Filament'),
    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \App\Livewire\Auth\Login::class,
        ],
    ],
    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
    ],
    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
    ],
    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
    ],
    'livewire' => [
        'namespace' => 'App\\Filament',
    ],
    'dark_mode' => [
        'enabled' => env('FILAMENT_DARK_MODE', false),
    ],
    'database_notifications' => [
        'enabled' => env('FILAMENT_DATABASE_NOTIFICATIONS', false),
    ],
    'broadcasting' => [
        'enabled' => env('FILAMENT_BROADCASTING', false),
    ],
    'layout' => [
        'forms' => [
            'actions' => [
                'alignment' => 'left',
            ],
        ],
        'footer' => [
            'should_show_logo' => false,
        ],
        'max_content_width' => 'full',
        'notifications' => [
            'vertical_alignment' => 'top',
            'alignment' => 'right',
        ],
        'sidebar' => [
            'is_collapsible_on_desktop' => true,
            'groups' => [
                'are_collapsible' => true,
            ],
            'width' => null,
            'collapsed_width' => null,
        ],
        'tables' => [
            'actions' => [
                'position' => 'end',
            ],
        ],
    ],
    'middleware' => [
        'base' => [
            'web',
            'auth',
        ],
        'auth' => [
            'verify' => true,
        ],
    ],
    'database' => [
        'seeders' => [
            'should_seed' => false,
        ],
    ],
    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),
]; 