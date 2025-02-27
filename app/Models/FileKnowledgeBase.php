<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileKnowledgeBase extends Model
{
    protected $fillable = [
        'file_name',
        'file_type',
        'content',
        'embedding',
        'user_id'
    ];

    protected $casts = [
        'embedding' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}