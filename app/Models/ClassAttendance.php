<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_room_id',
        'subject_id',
        'teacher_id',
        'tanggal',
        'jam_ke',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_ke' => 'integer'
    ];

    // Relasi ke siswa
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke kelas
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    // Relasi ke mata pelajaran
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // Relasi ke guru
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    // Scope untuk filter berdasarkan kelas
    public function scopeForClassRoom($query, $classRoomId)
    {
        return $query->where('class_room_id', $classRoomId);
    }
} 