<?php

namespace App\Http\Resources\Admin;

use App\Enums\RoleEnum;
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
        $role = $this->roles->first();
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'avatar_url' => $this->avatar ? url(Storage::url($this->avatar)) : null,
            'is_admin' => (bool) $this->is_admin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'department_id' => $this->department_id,
            'last_login_at' => $this->last_login_at,
            
            // Enhanced role information
            'role' => [
                'name' => $role?->name ?? 'N/A',
                'color' => RoleEnum::getBadgeColor($role?->name),
                'permissions' => $this->when($request->routeIs('admin.users.show'), 
                    fn() => $this->getAllPermissions()->pluck('name')
                ),
            ],
            
            // Relationship counts
            'exam_attempts_count' => $this->whenLoaded('examAttempts', function() {
                return $this->examAttempts->count();
            }),
            'certificates_count' => $this->whenLoaded('certificates', function() {
                return $this->certificates->count();
            }),
            
            // Optional relationships
            'certificates' => $this->when($request->routeIs('admin.users.show'), function () {
                return $this->certificates;
            }),
            'exam_attempts' => $this->when($request->routeIs('admin.users.show'), function () {
                return $this->examAttempts;
            }),
        ];
    }
}