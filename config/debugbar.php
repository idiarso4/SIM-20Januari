<?php

return [
    'enabled' => env('DEBUGBAR_ENABLED', env('APP_DEBUG', false)),
    'except' => [
        'telescope*',
        'horizon*',
    ],
    'storage' => [
        'enabled'    => true,
        'driver'     => 'file',
        'path'       => storage_path('debugbar'),
        'connection' => null,
        'provider'   => '',
        'hostname'   => '127.0.0.1',
        'port'       => 2304,
    ],
    'include_vendors' => true,
    'capture_ajax' => true,
    'add_ajax_timing' => true,
    'error_handler' => false,
    'clockwork' => false,
    'collectors' => [
        'phpinfo'         => true,
        'messages'        => true,
        'time'           => true,
        'memory'         => true,
        'exceptions'     => true,
        'log'           => true,
        'db'            => true,
        'views'         => true,
        'route'         => true,
        'auth'          => true,
        'gate'          => true,
        'session'       => true,
        'symfony_request' => true,
        'mail'          => true,
        'laravel'       => true,
        'events'        => true,
        'default_request' => false,
        'logs'          => false,
        'files'         => false,
        'config'        => false,
        'cache'         => false,
        'models'        => true,
        'livewire'      => true,
    ],
    'options' => [
        'auth' => [
            'show_name' => true,
        ],
        'db' => [
            'with_params'       => true,
            'backtrace'        => true,
            'backtrace_exclude_paths' => [],
            'timeline'         => false,
            'duration_background'  => true,
            'explain' => [
                'enabled' => false,
                'types' => ['SELECT'],
            ],
            'hints'             => false,
            'show_copy'       => false,
        ],
        'mail' => [
            'full_log' => false
        ],
        'views' => [
            'data' => false,
        ],
        'route' => [
            'label' => true
        ],
        'logs' => [
            'file' => null
        ],
        'cache' => [
            'values' => true
        ],
    ],
    'inject' => true,
    'route_prefix' => '_debugbar',
    'route_domain' => null,
    'theme' => env('DEBUGBAR_THEME', 'auto'),
    'debug_backtrace_limit' => 50,
]; 