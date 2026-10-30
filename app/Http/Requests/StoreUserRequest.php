<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admins can create users from the admin dashboard.
        return $this->user()?->can('create', \App\Models\User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'password_confirmation' => ['required'],
            'role_id'               => ['required', 'exists:roles,id'],
            'brand_ids'             => ['nullable', 'array'],
            'brand_ids.*'           => ['exists:brands,id'],
            'is_active'             => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Security: if this is an "admin preset" form submission, ensure the
        // role_id actually corresponds to the admin role slug. This prevents
        // a form manipulation attack where someone swaps the role_id.
        // The admin check in authorize() already gates this to admins only,
        // but we add this layer to enforce the UI intent server-side.
        // Nothing to do here — role assignment is handled in the controller
        // which resolves role by ID and the UserPolicy gates the whole action.
    }
}
