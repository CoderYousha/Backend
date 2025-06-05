<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordBehavior extends Model
{
    use HasFactory;

    protected $table = 'records_behaviors';
    protected $fillable = [
        'record_id',
        'behavior_id'
    ];
}