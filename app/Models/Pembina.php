<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembina extends Model
{
    protected $fillable = [
        'name',
        'photo',
        'term_period',
        'biography',
        'is_active',
    ];
}
