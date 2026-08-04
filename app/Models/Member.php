<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['department_id', 'cabinet_id', 'name', 'position', 'photo'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }
}
