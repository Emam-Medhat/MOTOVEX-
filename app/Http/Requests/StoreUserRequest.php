<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'   => ['required', Password::min(8)->letters()->numbers()],
            'role_id'    => ['required', 'exists:roles,id'],
            'brand_ids'  => ['nullable', 'array'],
            'brand_ids.*'=> ['exists:brands,id'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }
}
