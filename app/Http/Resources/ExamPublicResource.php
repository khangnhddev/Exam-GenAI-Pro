<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamPublicResource extends JsonResource
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
            'total_questions' => $this->total_questions,
            'topics' => $this->topics_covered ?? [],
            'image_url' => $this->image_url,
            'status' => $this->status,
            'category' => $this->category,
            'difficulty' => $this->difficulty,
            'question_types' => $this->whenLoaded('questions', function() {
                return $this->questions->pluck('type')->unique()->values();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}