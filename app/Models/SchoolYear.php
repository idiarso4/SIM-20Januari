<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'semester',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
} 