<?php

return [
    'guard' => 'web',
    'middleware' => ['web'],
    'auth_middleware' => 'auth',
    'passwords' => 'users',
    'username' => 'email',
    'email' => 'email',
    'views' => true,
    'home' => '/admin',
    
    'features' => [
        \Laravel\Fortify\Features::registration(),
        \Laravel\Fortify\Features::resetPasswords(),
        \Laravel\Fortify\Features::updateProfileInformation(),
        \Laravel\Fortify\Features::updatePasswords(),
        \Laravel\Fortify\Features::twoFactorAuthentication(),
    ],

    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],
]; 