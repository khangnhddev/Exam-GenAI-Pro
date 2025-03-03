<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'role' => $this->role,
            'department_id' => $this->department_id,
            'department' => $this->when($this->department, fn() => [
                'id' => $this->department->id,
                'name' => $this->department->name
            ]),
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
} 