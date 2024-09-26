<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class ApproveTransactionRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return auth()->user()->can('checker-actions');
    }
}
