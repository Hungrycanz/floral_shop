<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:100'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'card_message' => ['nullable', 'string'],
            'payment_method' => ['required', Rule::in(['cash', 'mobile_money', 'card'])],
            'delivery_zone_id' => ['nullable', 'integer', 'exists:delivery_zones,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.addons' => ['array'],
            'items.*.addons.*' => ['integer', 'exists:bouquet_options,id'],
        ];
    }
}
