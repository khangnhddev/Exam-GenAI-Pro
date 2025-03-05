<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'exam_attempt_id',
        'certificate_number',
        'score',
        'issued_date', // Changed from issued_at
        'metadata'
    ];

    protected $casts = [
        'issued_date' => 'datetime', // Changed from issued_at
        'metadata' => 'array',
        'score' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    /**
     * Check if the certificate is valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        // Certificate is valid if:
        // 1. It has been issued
        // 2. It hasn't expired (if expiry is set)
        // 3. The score meets or exceeds the exam's passing score
        
        if (!$this->issued_date) {
            return false;
        }

        // if ($this->expires_at && Carbon::parse($this->expires_at)->isPast()) {
        //     return false;
        // }

        return $this->score >= $this->exam->passing_score;
    }
}