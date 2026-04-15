<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['open', 'paid', 'cancelled'])],
            'payment_method' => ['sometimes', Rule::in(['cash', 'card', 'qris'])],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
