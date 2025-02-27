<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'exam_attempt_id',
        'certificate_number',
        'issued_at',
        'expires_at',
        'score',
        'status' // active, expired, revoked
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
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

    public function examAttempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function generateCertificateNumber(): string
    {
        return sprintf(
            'GENAI-%s-%s-%s',
            strtoupper(substr($this->exam->title, 0, 3)),
            $this->user_id,
            date('Ymd')
        );
    }

    public function isValid(): bool
    {
        return $this->status === 'active' && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }
}