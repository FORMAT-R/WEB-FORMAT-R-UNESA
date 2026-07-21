<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'location', 'organizer', 'start_date', 'end_date', 'status', 'image', 'lpj_file', 'proposal_file', 'participant_count'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function committees()
    {
        return $this->hasMany(EventCommittee::class);
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

