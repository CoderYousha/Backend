<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordIll extends Model
{
    use HasFactory;

    protected $table = 'records_ills';
    protected $fillable = [
        'record_id',
        'ill_id'
    ];
}
