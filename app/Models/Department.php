<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name', 'abbreviation', 'slug', 'description', 'image', 'doc_image_1', 'doc_image_2'];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
