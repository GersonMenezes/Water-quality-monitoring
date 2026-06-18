<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Permitimos que esses campos sejam preenchidos em massa
    protected $fillable = ['user_id', 'title', 'content'];
}
