<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $table = 'clinics';
    protected $fillable = [
        'clinic_name',
        'floor_number',
        'image',
    ];

    public function doctors()
    {
        return $this->belongsToMany(User::class, 'doctors_clinics', 'clinic_id', 'doctor_id');
    }
}
