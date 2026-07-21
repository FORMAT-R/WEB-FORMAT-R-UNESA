<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Birthday extends Model
{
    protected $fillable = ['member_id', 'name', 'department', 'position', 'photo', 'birth_date', 'celebration_status', 'message'];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

