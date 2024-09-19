<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Pool;
use App\Notifications\TransactionStatusUpdated;


class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->get(); 
        return view('transactions.index', compact('transactions'));
    }

    public function create() {
        return view('transactions.create');
    }
    
    public function store(Request $request) {
        $request->validate([
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);
    
        Transaction::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending',
        ]);
    
        return redirect('/transactions')->with('success', 'Transaction created successfully.');
    }

    public function pending()
    {
        $transactions = Transaction::where('status', 'pending')->get();
        return view('transactions.pending', compact('transactions'));
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'approved']);

        $user = $transaction->user;
        $wallet = $user->wallet;

        $pool = Pool::first(); 
        if ($transaction->type == 'credit') {
            $wallet->balance += $transaction->amount;
            $pool->balance -= $transaction->amount;
        } elseif ($transaction->type == 'debit') {
            $wallet->balance -= $transaction->amount;
            $pool->balance += $transaction->amount;
        }

        $wallet->save();
        $pool->save();

        $user->notify(new TransactionStatusUpdated($transaction));
        
        return redirect()->route('transactions.pending')->with('success', 'Transaction approved.');
    }


    public function reject($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'rejected']);

        $user->notify(new TransactionStatusUpdated($transaction));

        return redirect()->route('transactions.pending')->with('success', 'Transaction rejected.');
    }
}
