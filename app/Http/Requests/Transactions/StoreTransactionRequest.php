<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('marker-actions');

    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric',
            'type' => 'required|string|in:debit,credit',
            'description' => 'required|string',
        ];
    }
}
