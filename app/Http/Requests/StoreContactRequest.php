<?php

namespace App\Http\Requests;

use App\Models\Motorcycle;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:150'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'subject'      => ['required', 'string', 'max:150'],
            'enquiry_type' => ['required', 'in:general,test_drive,parts,service,product'],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'message'      => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $motorcycleId = $this->input('motorcycle_id');
            if ($this->input('enquiry_type') === 'product' && ! $motorcycleId) {
                $validator->errors()->add('motorcycle_id', 'Select a motorcycle for a product enquiry.');
            }
            if ($motorcycleId && ! Motorcycle::published()->whereKey($motorcycleId)->exists()) {
                $validator->errors()->add('motorcycle_id', 'This motorcycle is no longer available.');
            }
        });
    }
}
