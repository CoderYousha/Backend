<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Illnes extends Model
{
    use HasFactory;

    protected $table = 'illneses';
    protected $fillable = [
        'ill_name'
    ];
}