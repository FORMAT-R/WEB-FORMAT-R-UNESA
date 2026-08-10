<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCommittee extends Model
{
    protected $fillable = ['event_id', 'name', 'role', 'photo', 'sort_order'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
