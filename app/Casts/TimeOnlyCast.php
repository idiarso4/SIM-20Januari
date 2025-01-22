<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TimeOnlyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!$value) return null;
        return date('Y-m-d H:i:s', strtotime($value));
    }
} 