<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Server-side validation for the homepage test-ride mini-form.
 * Client-side validation mirrors these rules in Alpine.
 */
class StoreTestRideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:120'],
            'phone'               => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,30}$/'],
            'email'               => ['nullable', 'email', 'max:150'],
            'motorcycle_id'       => ['nullable', 'integer', 'exists:motorcycles,id'],
            'preferred_showroom'  => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Please tell us your name.',
            'phone.required' => 'A phone number is required so we can confirm your slot.',
            'phone.regex'     => 'That phone number does not look right.',
            'email.email'     => 'Enter a valid email, or leave it blank.',
        ];
    }
}
