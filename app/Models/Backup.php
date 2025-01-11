<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Backup extends Model
{
    protected $fillable = ['name', 'path', 'created_at'];
    public $timestamps = false;

    public static function getAllBackups()
    {
        return static::query()
            ->orderByDesc('created_at')
            ->get();
    }
} 