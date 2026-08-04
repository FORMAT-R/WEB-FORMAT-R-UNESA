<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'period', 'start_year', 'is_active', 'logo', 'vision', 'mission'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
