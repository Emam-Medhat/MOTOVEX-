<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Brand::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:100', 'unique:brands,name'],
            'description'  => ['required', 'string', 'max:2000'],
            'logo_url'     => ['nullable', 'url', 'max:500'],
            'website_url'  => ['nullable', 'url', 'max:500'],
            'country'      => ['nullable', 'string', 'max:100'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }
}
