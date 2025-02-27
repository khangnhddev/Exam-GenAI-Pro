<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningResource extends Model
{
    protected $fillable = [
        'title', 'type', 'description', 'url', 
        'file_path', 'category_id', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(ResourceCategory::class);
    }
}