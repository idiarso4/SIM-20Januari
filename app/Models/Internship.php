<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Internship extends Model
{
    protected $fillable = [
        'siswa_id',
        'office',
        'jenis_perusahaan',
        'deskripsi_perusahaan',
        'tanggal_selesai',
        'telepon',
        'status',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }
}