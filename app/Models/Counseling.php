<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counseling extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'counselor_id',
        'counseling_date',
        'start_time',
        'end_time',
        'type',
        'case_type',
        'problem_desc',
        'solution',
        'follow_up',
        'status',
        'notes',
    ];

    protected $casts = [
        'counseling_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function homeVisits()
    {
        return $this->hasMany(HomeVisit::class);
    }

    public function needsVisit(): bool
    {
        return $this->status === 'need_visit';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
