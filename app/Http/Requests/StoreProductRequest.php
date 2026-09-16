<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // TODO: once auth middleware/policies are wired in, replace this with
        // $this->user()?->isAdmin() - left open for now like the Spring Boot version
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'], // mirrors CHECK (price >= 0)
            'stock_quantity' => ['required', 'integer', 'min:0'], // mirrors CHECK (stock_quantity >= 0)
            'image_url' => ['nullable', 'string', 'max:255'],
            'occasion_ids' => ['sometimes', 'array'],
            'occasion_ids.*' => ['exists:occasions,id'],
        ];
    }
}
