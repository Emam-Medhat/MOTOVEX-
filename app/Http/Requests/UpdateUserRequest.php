<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('user')) ?? false;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:150', 'unique:users,email,' . $userId],
            'password'   => ['nullable', Password::min(8)->letters()->numbers()],
            'role_id'    => ['required', 'exists:roles,id'],
            'brand_ids'  => ['nullable', 'array'],
            'brand_ids.*'=> ['exists:brands,id'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }
}
