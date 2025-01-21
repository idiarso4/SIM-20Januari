<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPermit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'approved_by',
        'piket_guru_id',
        'permit_date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'notes',
        'approved_at',
        'returned_at',
    ];

    protected $casts = [
        'permit_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function piketGuru()
    {
        return $this->belongsTo(User::class, 'piket_guru_id');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
