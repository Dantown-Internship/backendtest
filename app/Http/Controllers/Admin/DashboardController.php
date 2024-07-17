<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Index function to retrieve total users and total transactions and render the admin dashboard view.
     */
    public function index(): View
    {
        $total_users = User::maker()->get()->count();
        $total_transactions = Transaction::all()->count();

        return view('admin-dashboard', compact('total_users', 'total_transactions'));
    }
}
