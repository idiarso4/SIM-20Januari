<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSchedule extends Model
{
    protected $fillable = [
        'guru_id',
        'class_room_id',
        'subject',
        'day',
        'start_time',
        'end_time',
        'room',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id')->where('role', 'guru');
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
} 