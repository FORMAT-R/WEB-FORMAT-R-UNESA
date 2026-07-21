<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestOfficer extends Model
{
    protected $fillable = ['member_id', 'name', 'department', 'photo', 'month', 'year', 'reason'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
