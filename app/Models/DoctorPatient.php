<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPatient extends Model
{
    use HasFactory;

    protected $table = 'doctors_patients';
    protected $fillable = [
        'doctor_id',
        'patient_id',
    ];
}