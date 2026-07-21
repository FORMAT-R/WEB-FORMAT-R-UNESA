<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRating extends Model
{
    protected $fillable = ['event_id', 'ip_address', 'rating'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
