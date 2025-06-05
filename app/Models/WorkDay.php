<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    use HasFactory;

    protected $table = 'work_days';
    protected $fillable = [
        'doctor_id',
        'day',
        'start_time',
        'end_time'
    ];
}