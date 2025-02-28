<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'image_alt',
        'description',
        'duration',
        'passing_score',
        'max_attempts',
        'total_questions',
        'status',
        'attempts_allowed'
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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return $this->getDefaultImage();
        }
        return Storage::url($this->image_path);
    }

    private function getDefaultImage()
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
                <rect width="200" height="200" fill="#4F46E5" opacity="0.1"/>
                <path d="M100 70c-16.569 0-30 13.431-30 30 0 16.569 13.431 30 30 30 16.569 0 30-13.431 30-30 0-16.569-13.431-30-30-30zm-45 30c0-24.853 20.147-45 45-45s45 20.147 45 45-20.147 45-45 45-45-20.147-45-45zm35 0c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10-10-4.477-10-10z" fill="#4F46E5"/>
            </svg>
        ');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }
}