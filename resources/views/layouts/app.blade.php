<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @filamentStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
    </head>
    <body class="antialiased">
        {{ $slot }}

        @livewireScripts
        @filamentScripts
        @stack('scripts')
        <script>
            window.addEventListener('livewire:load', function () {
                Livewire.on('refreshPage', () => {
                    window.location.reload();
                });
            });
        </script>
    </body>
</html> 