<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerGuidance extends Model
{
    protected $fillable = [
        'student_id',
        'class_room_id',
        'date',
        'type',
        'interest_talents',
        'career_plans',
        'guidance_results',
        'recommendations',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
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