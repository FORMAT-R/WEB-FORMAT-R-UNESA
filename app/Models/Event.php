<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'output', 'location', 'organizer', 'start_date', 'end_date', 'status', 'registration_link', 'image', 'lpj_file', 'proposal_file', 'participant_count'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function committees()
    {
        return $this->hasMany(EventCommittee::class);
    }

    public function speakers()
    {
        return $this->hasMany(EventSpeaker::class);
    }

    public function documentations()
    {
        return $this->hasMany(EventDocumentation::class);
    }

    public function ratings()
    {
        return $this->hasMany(EventRating::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }

    public function getRatingCountAttribute()
    {
        return $this->ratings()->count();
    }
}

