<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';
    protected $fillable = [
        'patient_id',
        'record_number',
        'full_name',
        'gender',
        'birth_date',
        'national_number',
        'title',
        'phone_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    public function ills(){
        return $this->belongsToMany(Illnes::class, RecordIll::class, 'record_id', 'ill_id');
    }
    
    public function medicines(){
        return $this->belongsToMany(Medicine::class, RecordMedicine::class, 'record_id', 'medicine_id');
    }
    
    public function behaviors(){
        return $this->belongsToMany(HealthyBehaviors::class, RecordBehavior::class, 'record_id', 'behavior_id');
    }
}