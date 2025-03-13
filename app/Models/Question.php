<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'exam_id',
        'question_text',
        'type',
        'points',
        'explanation',
        'expected_behavior',
        'evaluation_criteria',
        'min_passing_score',
        'challenge_description',
        'requirements',
    ];

    protected $casts = [
        'points' => 'integer',
        'evaluation_criteria' => 'array',
        'requirements' => 'array',
        'evaluation_criteria' => 'array',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Get the prompt evaluations for the question.
     */
    public function promptEvaluations(): HasMany
    {
        return $this->hasMany(PromptEvaluation::class)
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get evaluations for a specific attempt
     */
    public function getEvaluationForAttempt($attemptId)
    {
        return $this->promptEvaluations()
                    ->where('attempt_id', $attemptId)
                    ->first();
    }

    const TYPE_SINGLE_CHOICE = 'single_choice';
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_PROMPT = 'prompt';
}