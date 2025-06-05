<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordMedicine extends Model
{
    use HasFactory;

    protected $table = 'records_medicines';
    protected $fillable = [
        'record_id',
        'medicine_id'
    ];
}
