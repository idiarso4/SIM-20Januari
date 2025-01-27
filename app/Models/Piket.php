<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Piket extends Model
{
    use HasFactory;

    protected $table = 'piket';
    
    protected $fillable = [
        'tanggal',
        'guru_id',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
