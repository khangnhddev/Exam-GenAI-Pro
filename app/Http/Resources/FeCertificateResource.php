<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeCertificateResource extends JsonResource
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
            'exam_title' => $this->exam->title,
            'certificate_number' => $this->certificate_number,
            'issued_at' => $this->issued_date?->format('Y-m-d'),
            'expires_at' => $this->expires_at?->format('Y-m-d'),
            'score' => $this->score,
            'status' => $this->status,
            'is_valid' => $this->isValid(),
            'user' => [
                'id' => $this->user->id,
                'fullname' => $this->user->fullname,
                'email' => $this->user->email,
                'avatar_url' => $this->user->avatar_url
            ],
            'exam' => [
                'id' => $this->exam->id,
                'title' => $this->exam->title,
                'duration' => $this->exam->duration,
                'total_questions' => $this->exam->total_questions,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}