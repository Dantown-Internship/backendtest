<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $wallet = $user->wallet; // Assuming a relationship exists
        $approvedTransactions = $user->transactions()->where('status', 'approved')->get();
        return view('transactions.show', compact('wallet', 'approvedTransactions'));
    }
}
