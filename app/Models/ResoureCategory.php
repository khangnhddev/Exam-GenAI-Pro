<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function resources()
    {
        return $this->hasMany(LearningResource::class, 'category_id');
    }
}