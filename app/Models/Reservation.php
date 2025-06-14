<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $fillable = [
        'clinic_transfer_id',
        'doctor_id',
        'patient_id',
        'date',
        'time',
        'status',
        'type',
        'description'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function clinicTransfer()
    {
        return $this->belongsTo(Clinic::class, 'clinic_transfer_id', 'id');
    }
}