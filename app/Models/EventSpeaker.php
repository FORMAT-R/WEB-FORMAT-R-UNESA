<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSpeaker extends Model
{
    protected $fillable = ['event_id', 'name', 'role', 'topic', 'photo', 'sort_order'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
