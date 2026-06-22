<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WaterReading extends Model
{
    use HasFactory; // Mocker

    protected $fillable = ['user_id', 'ph_level', 'temperature']; // Permitimos que esses campos sejam preenchidos em massa
}
