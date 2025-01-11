<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    protected $fillable = [
        'student_id',
        'class_room_id',
        'type',
        'subject',
        'assessment_name',
        'date',
        'score',
        'description',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'score' => 'float',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
} 