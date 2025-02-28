<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileKnowledgeBase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'content',
        'status',
        'chunks_count',
        'embedding'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'chunks_count' => 'integer',
        'embedding' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}