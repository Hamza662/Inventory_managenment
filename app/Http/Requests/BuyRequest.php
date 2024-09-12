<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'product_rows' => 'required|array',
            'product_rows.*.product_id' => 'required|exists:products,id',
            'product_rows.*.quantity' => 'required|integer|min:1',
            'product_rows.*.unit_price' => 'required|numeric|min:0',
            'product_rows.*.total_price' => 'required|numeric|min:0',
            'product_rows.*.description' => 'nullable|string|max:255',
        ];
    }
}
