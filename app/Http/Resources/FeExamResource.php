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
        $lastAttempt = $this->attempts->last();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'passing_score' => $this->passing_score,
            'questions_count' => $this->questions_count,
            'attempts_allowed' => $this->attempts_allowed,
            'attempts_made' => $this->attempts->count(),
            'status' => $this->status,
            'can_attempt' => $this->attempts->count() < $this->attempts_allowed,
            'last_attempt' => $lastAttempt ? [
                'id' => $lastAttempt->id,
                'score' => $lastAttempt->score,
                'status' => $lastAttempt->status,
                'started_at' => $lastAttempt->started_at,
                'completed_at' => $lastAttempt->completed_at,
                'passed' => $lastAttempt->score >= $this->passing_score
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            $this->mergeWhen($request->routeIs('*.show'), [
                'settings' => $this->settings,
                'total_points' => $this->questions->sum('points'),
                'certificate' => $lastAttempt && $lastAttempt->certificate ? [
                    'id' => $lastAttempt->certificate->id,
                    'issued_at' => $lastAttempt->certificate->issued_at,
                    'download_url' => route('certificates.download', $lastAttempt->certificate->id)
                ] : null
            ])
        ];
    }
}