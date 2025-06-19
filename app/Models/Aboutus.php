<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aboutus extends Model
{
    use HasFactory;

    protected $table = 'aboutus';
    protected $fillable = [
        'center_name',
        'title',
        'phone_number',
        'director_name',
        'gmail',
        'website',
        'facebook'
    ];
}