<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificatePassedMail;

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

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($attempt) {
            // Check if attempt was just completed and passed
            if (
                $attempt->isDirty('status') &&
                $attempt->status === 'completed' &&
                $attempt->score >= $attempt->exam->passing_score
            ) {
                // Send congratulatory email immediately without queue
                Mail::to($attempt->user->email)
                    ->send(new CertificatePassedMail($attempt));
            }
        });
    }

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

    /**
     * Get the prompt evaluations for the attempt.
     */
    public function promptEvaluations()
    {
        return $this->hasMany(PromptEvaluation::class, 'attempt_id')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get all evaluations for this attempt with questions.
     */
    public function getEvaluationsWithQuestions()
    {
        return $this->promptEvaluations()
                    ->with('question')
                    ->get()
                    ->mapWithKeys(function ($evaluation) {
                        return [$evaluation->question_id => [
                            'feedback' => $evaluation->ai_feedback,
                            'score' => $evaluation->ai_score,
                            'is_passed' => $evaluation->is_passed,
                            'criteria' => $evaluation->evaluation_criteria
                        ]];
                    });
    }
}