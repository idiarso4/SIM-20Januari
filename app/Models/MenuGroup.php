<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuGroup extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'order'
    ];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'group_id');
    }
} 