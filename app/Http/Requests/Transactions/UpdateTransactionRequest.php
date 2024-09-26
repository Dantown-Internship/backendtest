<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return auth()->user()->can('marker-actions');

    }

    public function rules(): array
    {
        return [
            'description' => 'required|string',
        ];
    }
}
