<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'status',
        'passing_score',
        'attempts_allowed',
        'total_questions',
        'topics_covered',
        'source',
        'slug',
    ];

    // Optional: Add status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    // Add source constants
    const SOURCE_MANUAL = 'manual';
    const SOURCE_AI = 'ai_generated';

    protected $casts = [
        'topics_covered' => 'array',
        'duration' => 'integer',
        'passing_score' => 'integer',
        'attempts_allowed' => 'integer',
        'total_questions' => 'integer',
    ];

    protected $attributes = [
        'total_questions' => 0,
        'status' => 'draft'
    ];

    protected $appends = ['image_url'];

    protected $cascadeDeletes = ['questions'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($exam) {
            if (empty($exam->slug)) {
                $baseSlug = Str::slug($exam->title);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $exam->slug = $slug;
            }
        });

        static::updating(function ($exam) {
            if ($exam->isDirty('title')) {
                $baseSlug = Str::slug($exam->title);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)
                           ->where('id', '!=', $exam->id)
                           ->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $exam->slug = $slug;
            }
        });
    }

    /**
     * getImageUrlAttribute
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return $this->getDefaultImage();
        }
        return Storage::url($this->image_path);
    }

    /**
     * getDefaultImage
     */
    private function getDefaultImage()
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
                <rect width="200" height="200" fill="#4F46E5" opacity="0.1"/>
                <path d="M100 70c-16.569 0-30 13.431-30 30 0 16.569 13.431 30 30 30 16.569 0 30-13.431 30-30 0-16.569-13.431-30-30-30zm-45 30c0-24.853 20.147-45 45-45s45 20.147 45 45-20.147 45-45 45-45-20.147-45-45zm35 0c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10-10-4.477-10-10z" fill="#4F46E5"/>
            </svg>
        ');
    }

    /**
     * questions
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function isAiGenerated(): bool
    {
        return $this->source === self::SOURCE_AI;
    }

    public function isManuallyCreated(): bool
    {
        return $this->source === self::SOURCE_MANUAL;
    }
}