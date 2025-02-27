<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'passing_score' => $this->passing_score,
            'max_attempts' => $this->max_attempts,
            'status' => $this->status,
            'questions_count' => $this->questions_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
