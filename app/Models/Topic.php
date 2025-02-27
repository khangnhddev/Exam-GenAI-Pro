<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon'];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
}