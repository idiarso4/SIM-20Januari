<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Major extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }
} 