<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'date',
        'time',
    ];

    public function doctor(){
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    public function patient(){
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }
}