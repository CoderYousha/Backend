<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthyBehaviors extends Model
{
    use HasFactory;

    protected $table = 'healthy_behaviors';
    protected $fillable = [
        'behavior'
    ];
}
