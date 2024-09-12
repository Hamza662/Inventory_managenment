<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'payment_status' => 'required|in:full_paid,full_due,partial_paid',
            'partial_amount' => 'nullable|numeric|min:0',
            'customer_id' => 'required|exists:customers,id',
            'rows' => 'required', // Ensure 'rows' is an array
            'rows.*.product_id' => 'required|exists:products,id', // Each row must have a valid 'product_id'
            'rows.*.quantity' => 'required|numeric|min:1', // Each row must have a numeric 'quantity' of at least 1
            'rows.*.price' => 'required|numeric|min:0', // Each row must have a numeric 'price' of at least 0
            'rows.*.discount' => 'nullable|numeric|min:0|max:100', // 'discount' is optional but must be a number between 0 and 100
            'total_price' => 'required|numeric|min:0',
            'rows.*.category_id' => 'required|exists:categories,id',
        ];
    }
}
