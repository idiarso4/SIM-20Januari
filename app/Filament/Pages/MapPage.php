<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MapPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Location Map';
    protected static ?string $navigationGroup = 'Attendance Management';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.map-page';
}
