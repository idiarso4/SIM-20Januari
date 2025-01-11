<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'counseling_id',
        'student_id',
        'counselor_id',
        'visit_date',
        'visit_time',
        'address',
        'met_with',
        'discussion_points',
        'agreements',
        'recommendations',
        'follow_up_plan',
        'status',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visit_time' => 'datetime',
    ];

    public function counseling()
    {
        return $this->belongsTo(Counseling::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRescheduled(): bool
    {
        return $this->status === 'rescheduled';
    }
}
