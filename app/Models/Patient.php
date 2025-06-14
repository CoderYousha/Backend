<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Patient extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'patients';
    protected $fillable = [
        'record_number',
        'title',
        'name',
        'username',
        'email',
        'password',
        'phone_number',
        'birth_date',
        'address',
        'status',
        'gender',
        'image',
        'length',
        'weight'
    ];

    public function ills()
    {
        return $this->belongsToMany(Illnes::class, PatientIll::class, 'patient_id', 'ill_id');
    }

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, PatientMedicine::class, 'patient_id', 'medicine_id');
    }

    public function behaviors()
    {
        return $this->belongsToMany(HealthyBehaviors::class, PatientBehavior::class, 'patient_id', 'behavior_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'patient_id', 'id');
    }
}
