<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id'
        ];

        if ($this->isMethod('POST')) {
            $rules['code'] = 'required|string|unique:departments,code|max:50';
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['code'] = [
                'required',
                'string',
                'max:50',
                Rule::unique('departments')->ignore($this->department->id)
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The department name is required.',
            'code.required' => 'The department code is required.',
            'code.unique' => 'This department code has already been taken.',
            'manager_id.exists' => 'The selected manager does not exist.',
            'user_ids.*.exists' => 'One or more selected users do not exist.'
        ];
    }
} 