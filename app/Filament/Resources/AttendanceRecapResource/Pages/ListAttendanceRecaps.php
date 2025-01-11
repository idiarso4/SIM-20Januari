<?php

namespace App\Filament\Resources\AttendanceRecapResource\Pages;

use App\Filament\Resources\AttendanceRecapResource;
use App\Filament\Resources\AttendanceRecapResource\Widgets\AttendanceStatsOverview;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceRecaps extends ListRecords
{
    protected static string $resource = AttendanceRecapResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AttendanceStatsOverview::class,
        ];
    }
}
