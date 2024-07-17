<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Create a new transaction based on the provided request data.
     *
     * @param  Request  $request  The request containing the transaction details
     * @return RedirectResponse with success or error message based on transaction creation status
     */
    public function create(Request $request): RedirectResponse
    {
        if (! Auth::user()->hasWallet()) {
            return redirect()->back()->with('error', 'Please, create a wallet first');
        }

        $validated = $request->validate([
            'type' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
        ]);

        $amount = number_format($transaction->amount, 2);
        $name = $transaction->user->name;

        TransactionActivity::create([
            'transaction_id' => $transaction->id,
            'activity' => "$name initiated a $transaction->type transaction of NGN $amount",
        ]);

        return redirect()->back()->with('success', 'Transaction created successfully');
    }
}
