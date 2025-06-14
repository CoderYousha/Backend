<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicine extends Model
{
    use HasFactory;

    protected $table = 'patients_medicines';
    protected $fillable = [
        'patient_id',
        'medicine_id'
    ];
}
