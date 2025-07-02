<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaints';
    protected $fillable = [
        'user_id',
        'title',
        'complaint'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'user_id', 'id');
    }
}