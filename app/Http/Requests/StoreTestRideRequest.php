<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestRideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:120'],
            'phone'              => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,30}$/'],
            'email'              => ['required', 'email', 'max:150'],
            'motorcycle_id'      => ['nullable', 'integer', 'exists:motorcycles,id'],
            'showroom_id'        => ['nullable', 'integer', 'exists:showrooms,id'],
            'preferred_showroom' => ['nullable', 'string', 'max:150'],
            'preferred_date'     => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time'     => ['nullable', 'string', 'max:20'],
            'notes'              => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Please enter your full name.',
            'email.required'         => 'An email address is required.',
            'email.email'            => 'Please enter a valid email address.',
            'phone.required'         => 'A phone number is required so we can confirm your slot.',
            'phone.regex'            => 'That phone number does not look right.',
            'preferred_date.after_or_equal' => 'The preferred date must be today or in the future.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->filled('motorcycle_id')) {
                foreach (['preferred_date', 'preferred_time'] as $field) {
                    if (! $this->filled($field)) {
                        $validator->errors()->add($field, 'Choose a preferred date and time for this motorcycle.');
                    }
                }
            }
        });
    }
}
