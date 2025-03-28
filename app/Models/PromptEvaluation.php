<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptEvaluation extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'user_answer',
        'ai_feedback',
        'ai_score',
        'is_passed',
        'evaluation_criteria',
        'evaluated_at'
    ];

    protected $casts = [
        'is_passed' => 'boolean',
        'ai_score' => 'integer',
        'evaluation_criteria' => 'array',
        'evaluated_at' => 'datetime'
    ];

    /**
     * Get the attempt that owns the evaluation.
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    /**
     * Get the question being evaluated.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}