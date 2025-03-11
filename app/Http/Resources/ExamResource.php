<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'topics_covered' => $this->topics_covered,
            'passing_score' => $this->passing_score,
            'max_attempts' => $this->max_attempts,
            'total_questions' => $this->total_questions,
            'status' => $this->status,
            'questions_count' => $this->whenCounted('questions'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'slug' => $this->slug,
            'source' => $this->source,
            'is_ai_generated' => $this->isAiGenerated(),
        ];
    }
}
