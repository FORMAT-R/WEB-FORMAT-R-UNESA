<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['department_id', 'name', 'position', 'photo', 'birth_date'];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
