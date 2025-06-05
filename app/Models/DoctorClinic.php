<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorClinic extends Model
{
    use HasFactory;

    protected $table = 'doctors_clinics';
    protected $fillable = [
        'clinic_id',
        'doctor_id',
    ];
}