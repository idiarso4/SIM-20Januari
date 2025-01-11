<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDetail extends Model
{
    protected $fillable = [
        'user_id',
        'nipd',
        'gender',
        'nisn',
        'birth_place',
        'birth_date',
        'nik',
        'religion',
        'address',
        'rt',
        'rw',
        'dusun',
        'kelurahan',
        'kecamatan',
        'postal_code',
        'residence_type',
        'transportation',
        'phone',
        'mobile_phone',
        'email',
        'skhun',
        'kps_recipient',
        'kps_number',
        'class_group',
        'un_number',
        'ijazah_number',
        'kip_recipient',
        'kip_number',
        'kip_name',
        'kks_number',
        'birth_certificate_no',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'pip_eligible',
        'pip_eligible_reason',
        'special_needs',
        'previous_school',
        'child_order',
        'latitude',
        'longitude',
        'kk_number',
        'weight',
        'height',
        'head_circumference',
        'siblings_count',
        'distance_to_school',
        'father_name',
        'father_birth_year',
        'father_education',
        'father_occupation',
        'father_income',
        'father_nik',
        'mother_name',
        'mother_birth_year',
        'mother_education',
        'mother_occupation',
        'mother_income',
        'mother_nik',
        'guardian_name',
        'guardian_birth_year',
        'guardian_education',
        'guardian_occupation',
        'guardian_income',
        'guardian_nik',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'kps_recipient' => 'boolean',
        'kip_recipient' => 'boolean',
        'pip_eligible' => 'boolean',
        'father_birth_year' => 'integer',
        'mother_birth_year' => 'integer',
        'guardian_birth_year' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'head_circumference' => 'decimal:2',
        'distance_to_school' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 