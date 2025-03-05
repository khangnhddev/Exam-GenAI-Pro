<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'passing_score' => $this->passing_score,
            'attempts_allowed' => $this->attempts_allowed,
            'total_questions' => $this->questions_count,
            'topics' => json_decode($this->topics_covered) ?? [],
            'image_url' => $this->image_url,
            'attempts' => $this->when($this->attempts, function() {
                return $this->attempts->map(function($attempt) {
                    return [
                        'id' => $attempt->id,
                        'score' => $attempt->score,
                        'status' => $attempt->status,
                        'completed_at' => $attempt->completed_at,
                        'created_at' => $attempt->created_at,
                        'attempt_number' => $attempt->attempt_number,
                    ];
                });
            }),
            'has_attempts' => $this->attempts->isNotEmpty(),
            'last_attempt' => $this->when($this->attempts->isNotEmpty(), function() {
                $lastAttempt = $this->attempts->sortByDesc('created_at')->first();
                return [
                    'score' => $lastAttempt->score,
                    'passed' => $lastAttempt->score >= $this->passing_score,
                    'completed_at' => $lastAttempt->completed_at,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}