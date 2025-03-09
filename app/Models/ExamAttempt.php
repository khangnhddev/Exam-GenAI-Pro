<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'started_at',
        'completed_at',
        'score',
        'status',
        'attempt_number',
        'answers'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'integer',
        'answers' => 'array'
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function hasPassed(): bool
    {
        return $this->score >= $this->exam->passing_score;
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function getRemainingTime(): int
    {
        if (!$this->isInProgress()) {
            return 0;
        }

        $endTime = $this->started_at->addMinutes($this->exam->duration);
        $remainingSeconds = $endTime->diffInSeconds(now(), false);

        return max(0, $remainingSeconds);
    }
}