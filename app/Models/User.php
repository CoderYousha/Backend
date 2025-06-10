<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone_number',
        'account_type',
        'medical_specialization',
        'work_section',
        'birth_date',
        'address',
        'status',
        'image'
    ];

    public function holidays()
    {
        return $this->hasMany(Holiday::class, 'user_id', 'id');
    }

    public function doctors()
    {
        return $this->belongsToMany(User::class, DoctorPatient::class, 'patient_id', 'doctor_id');
    }

    public function patients()
    {
        return $this->belongsToMany(User::class, DoctorPatient::class, 'doctor_id', 'patient_id');
    }

    public function record()
    {
        return $this->hasOne(MedicalRecord::class, 'patient_id', 'id');
    }

    public function doctorReservations()
    {
        return $this->hasMany(Reservation::class, 'doctor_id', 'id');
    }

    public function patientReservations()
    {
        return $this->hasMany(Reservation::class, 'patient_id', 'id');
    }

    public function workDays()
    {
        return $this->hasMany(WorkDay::class, 'doctor_id', 'id');
    }
}