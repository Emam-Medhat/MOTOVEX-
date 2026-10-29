<?php

namespace App\Http\Requests;

use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;

class StoreMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Motorcycle::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'brand_id'             => ['required', 'exists:brands,id'],
            'model_name'           => ['required', 'string', 'max:150'],
            'price'                => ['required', 'numeric', 'min:0'],
            'year'                 => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 2)],
            'category'             => ['required', 'in:sport,naked,adventure,cruiser,scooter,off-road'],
            'engine_capacity'      => ['nullable', 'numeric', 'min:0', 'max:5000'],
            'description'          => ['required', 'string', 'max:3000'],
            'short_description'    => ['nullable', 'string', 'max:300'],
            'image'                => ['nullable', 'image', 'max:4096'],
            'additional_images'    => ['nullable', 'array'],
            'additional_images.*'  => ['image', 'max:4096'],
            'availability_status'  => ['required', 'in:available,reserved,sold'],
            'condition'            => ['required', 'in:new,used'],
            'is_published'         => ['sometimes', 'boolean'],
            'is_featured'          => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $brandId = $this->input('brand_id');

            if (! $user || ! $brandId) {
                return;
            }

            if ($user->isAdmin()) {
                return;
            }

            $brand = Brand::find($brandId);

            if (! $brand || ! $user->canManageBrand($brand)) {
                $validator->errors()->add('brand_id', 'You are not assigned to this brand.');
            }
        });
    }
}
