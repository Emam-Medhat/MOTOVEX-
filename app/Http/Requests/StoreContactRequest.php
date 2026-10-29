<?php

namespace App\Http\Requests;

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
            'enquiry_type' => ['required', 'in:general,test_drive,parts,service'],
            'message'      => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }
}
