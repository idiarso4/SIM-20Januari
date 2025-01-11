<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Resources\RoleResource as ShieldRoleResource;

class RoleResource extends ShieldRoleResource
{
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 2;
} 