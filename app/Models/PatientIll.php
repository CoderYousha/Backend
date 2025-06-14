<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientIll extends Model
{
    use HasFactory;

    protected $table = 'patients_ills';
    protected $fillable = [
        'patient_id',
        'ill_id'
    ];
}