<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    protected $table = 'knowledge_bases';
    
    protected $fillable = [
        'content',
        'source',
        'embedding'
    ];

    protected $casts = [
        'embedding' => 'array'
    ];
}