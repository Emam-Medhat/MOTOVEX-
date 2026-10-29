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
            'account_type'       => ['required', 'string', 'in:customer,data_entry,admin'],
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
            'email.unique'   => 'This email is already registered, try signing in instead.',
            'terms.accepted' => 'You must accept the terms and privacy policy to create an account.',
        ];
    }
}
