<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientBehavior extends Model
{
    use HasFactory;

    protected $table = 'patients_behaviors';
    protected $fillable = [
        'patient_id',
        'behavior_id'
    ];
}