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
        'issued_date',
        'expiry_date',
        'status',
        'score'
    ];

    protected $casts = [
        'issued_date' => 'datetime',
        'expiry_date' => 'datetime',
        'score' => 'float'
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
               (!$this->expiry_date || $this->expiry_date->isFuture());
    }
}