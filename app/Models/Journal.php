<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal extends Model
{
    protected $fillable = [
        'internship_id',
        'date',
        'activity_description',
        'learning_outcomes',
        'challenges',
        'solutions',
        'status',
        'feedback',
        'attachment',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function student()
    {
        return $this->internship->student();
    }
} 