<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class GetAllTransactionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ( auth()->user()->can('marker-actions') || auth()->user()->can('checker-actions') );
    }
}
