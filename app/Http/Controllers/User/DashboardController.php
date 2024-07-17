<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Retrieves the authenticated user's total transactions and balance, and returns a view with the user data.
     */
    public function index(): View
    {
        $auth_user = Auth::user();

        $total_transactions = $auth_user->totalTransactions();
        if ($auth_user->hasWallet()) {
            $total_balance = $auth_user->wallet->formatted_balance;
        } else {
            $total_balance = null;
        }

        return view('user-dashboard', compact('auth_user', 'total_transactions', 'total_balance'));
    }
}
