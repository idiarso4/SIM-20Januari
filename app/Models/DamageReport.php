<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'location',
        'damage_description',
        'priority',
        'status',
        'reported_date',
        'reported_by',
        'notes',
    ];

    protected $casts = [
        'reported_date' => 'date',
    ];
} 