<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">SISTEM INFORMASI</h1>
                <h2 class="text-2xl mb-8">SMKN 1 PUNGGELAN</h2>
                <a href="{{ route('filament.admin.auth.login') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Masuk ke Sistem
                </a>
            </div>
        </div>
    </body>
</html>
