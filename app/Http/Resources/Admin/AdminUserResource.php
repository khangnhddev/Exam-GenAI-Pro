<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdminUserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'avatar_url' => $this->avatar ? url(Storage::url($this->avatar)) : null,
            'is_admin' => (bool) $this->is_admin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_login_at' => $this->last_login_at,
            // Add relationships data
            'exam_attempts_count' => $this->whenLoaded('examAttempts', function() {
                return $this->examAttempts->count();
            }),
            'certificates_count' => $this->whenLoaded('certificates', function() {
                return $this->certificates->count();
            }),
            // Add optional relationships
            'certificates' => $this->when($request->routeIs('admin.users.show'), function () {
                return $this->certificates;
            }),
            'exam_attempts' => $this->when($request->routeIs('admin.users.show'), function () {
                return $this->examAttempts;
            }),
        ];
    }
}