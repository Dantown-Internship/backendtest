<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    //

    public function create(Request $request)
    {
        $validate = \Validator::make($request->all(), [
            'description' => 'required',
            'amount' => 'required|integer',
            'type' => 'required|in:debit,credit',
        ]);

        if ($validate->errors()->count() > 0) {
            return back()->with('error', $validate->errors());
        }
        try {
            \DB::beginTransaction();

            $user = \Auth::user();
            $wallet = $user->wallet;
            if ($request->type == 'debit' && $request->amount > $wallet->balance) {
                return back()->with('error', 'Insufficient wallet balance');
            }

            Transaction::create(array_merge($request->all(), [
                'wallet_id' => $wallet->id,
                'status' => 'pending',
                'amount' => $request->amount,
                'transaction_type' => $request->type
            ]));
            \DB::commit();


            return back()->with('success', '
            Transaction created');
        } catch (\Exception $ex) {
            dd($ex->getMessage());
            return back()->with('error', $ex->getMessage());
        }

    }
    public function approve(int $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $transaction->status = 'approved';
        $transaction->save();
        $wallet = Wallet::find($transaction->wallet_id);
        if ($transaction->transaction_type == 'credit') {
            $wallet->balance += $transaction->amount;
        } else {
            $wallet->balance -= $transaction->amount;
        }
        $wallet->save();
        return response()->json(
            [
                'message' => 'Transaction approved.',
                'redirect' => route('dashboard')
            ]
        );
    }
    public function reject(int $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $transaction->status = 'reject';
        $transaction->save();
        return response()->json(
            [
                'message' => 'Transaction rejected.',
                'redirect' => route('dashboard')
            ]
        );
    }
}