<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'passing_score',
        'max_attempts',
        'total_questions',
        'status'
    ];

    protected $casts = [
        'duration' => 'integer',
        'passing_score' => 'integer',
        'total_questions' => 'integer',
        'attempts_allowed' => 'integer'
    ];

    protected $attributes = [
        'total_questions' => 0,
        'status' => 'draft'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }
}