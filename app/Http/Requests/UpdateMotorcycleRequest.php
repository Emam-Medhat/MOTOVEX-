<?php

namespace App\Http\Requests;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('motorcycle')) ?? false;
    }

    public function rules(): array
    {
        return [
            'brand_id'             => ['required', 'exists:brands,id'],
            'model_name'           => ['required', 'string', 'max:150'],
            'price'                => ['required', 'numeric', 'min:0'],
            'rental_price_per_day' => ['nullable', 'numeric', 'gt:0'],
            'assigned_manager_id'  => ['nullable', 'integer', 'exists:users,id'],
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
                $managerId = $this->input('assigned_manager_id');
                if ($managerId && ! User::query()->whereKey($managerId)->whereHas(
                    'roles',
                    fn ($query) => $query->whereIn('slug', ['data_entry', 'sales_agent'])
                )->exists()) {
                    $validator->errors()->add('assigned_manager_id', 'Select an authorized manager.');
                }

                return;
            }

            $brand = Brand::find($brandId);

            if (! $brand || ! $user->canManageBrand($brand)) {
                $validator->errors()->add('brand_id', 'You are not assigned to this brand.');
            }

            if ($this->filled('assigned_manager_id')) {
                $validator->errors()->add('assigned_manager_id', 'Only administrators can assign a manager.');
            }
        });
    }
}
