<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:100'],
            'email'              => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone'              => ['required', 'string', 'max:30'],
            'country'            => ['nullable', 'string', 'max:100'],
            'city'               => ['nullable', 'string', 'max:100'],
            // Only 'customer' and 'merchant' are allowed from public registration.
            // 'admin' is intentionally excluded — admin accounts can only be
            // created by an existing admin from the Users management page.
            'account_type'       => ['required', 'string', 'in:customer,merchant'],
            'password'           => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms'              => ['accepted'],
            'newsletter'         => ['nullable', 'boolean'],
            // Simple honeypot: must stay empty.
            'website'            => ['nullable', 'size:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'         => 'This email is already registered, try signing in instead.',
            'terms.accepted'       => 'You must accept the terms and privacy policy to create an account.',
            'account_type.in'      => 'Invalid account type. Only Customer or Merchant registration is allowed.',
        ];
    }
}
