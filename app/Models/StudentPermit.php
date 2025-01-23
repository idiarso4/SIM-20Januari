<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function piketGuru(): BelongsTo
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
