<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Skansapung Presensi</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    <style>
        #map {
            height: 400px;
        }
    </style>
</head>
<body>
    {{ $slot }}

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @livewireScripts
</body>
</html>