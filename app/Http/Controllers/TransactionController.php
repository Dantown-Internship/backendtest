<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\SystemPool;
use App\Models\Transaction;
use App\Http\Requests\ReviewTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return (auth()->user()->role === config('constant.role.checker')) 
        ? view('transaction.index')->with('user', auth()->user())->with('checker', config('constant.role.checker'))->with('maker', config('constant.role.maker'))->with('rejected', config('constant.status.rejected'))->with('pending', config('constant.status.pending'))->with('transactions', Transaction::all())
        : view('transaction.index')->with('user', auth()->user())->with('checker', config('constant.role.checker'))->with('maker', config('constant.role.maker'))->with('rejected', config('constant.status.rejected'))->with('pending', config('constant.status.pending'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transaction.create')->with('user', auth()->user())->with('transaction_types', config('constant.transaction.type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function check()
    {
        return view('transaction.check')->with('user', auth()->user());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $request->merge([
            'user_id' => auth()->user()->id,
            'third_party' => env('APP_NAME')
        ]);
        $transaction = Transaction::create($request->all());

        $request->session()->flash('message', 'Your transaction will be reviewed soon');
        
        Log::info("A transaction for {$transaction->user->name} has been created");

        return redirect()->route('transaction');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        if (!Gate::allows('edit-transaction', $transaction)) {
            Log::notice("{$transaction->user->name} tried to edit a transaction of status {$transaction->status}");
            abort(403, 'Transaction cannot be edited because it is already approved or pending.');
        }

        return view('transaction.edit')->with('user', auth()->user())->with('transaction_types', config('constant.transaction.type'))->with('note', $transaction->note)->with('transaction', $transaction);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function review(Transaction $transaction)
    {
        if (!Gate::allows('review-transaction', $transaction)) {
            abort(403, 'Transaction cannot be reviewed because it is already approved or rejected.');
        }
        return view('transaction.review')->with('user', auth()->user())->with('transaction', $transaction)->with('decisions', config('constant.status'));
    }

    public function decide(ReviewTransactionRequest $request, Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            if($request->status !== config('constant.status.pending')) {
                if($request->status === config('constant.status.rejected')) {
                    $transaction->updateNote($request->note);
                } else {
                    $systemPool = SystemPool::where('name', config('constant.system_pool.name'))->first();
                    if($transaction->type === config('constant.transaction.type.credit')){
                        $transaction->user->wallet->creditBalance($transaction->amount);
                        $systemPool->debitBalance($transaction->amount);
                    } else {
                        $transaction->user->wallet->debitBalance($transaction->amount);
                        $systemPool->creditBalance($transaction->amount);
                    }
                    if ($request->note) $transaction->updateNote($request->note);
                }
                $transaction->updateStatus($request->status);
                Log::alert("Transaction has been {$transaction->status}");
            } else {
                $request->session()->flash('message', "Transaction is still $request->status");
                Log::warning("Transaction is still $transaction->status");
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', $e->getMessage());
            Log::error("Transaction failed to update: ". $e->getMessage());
        }

        return redirect()->route('transaction');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $request->merge([
            'status' => config('constant.status.pending')
        ]);

        $transaction->update($request->all());

        $request->session()->flash('message', 'Your transaction will be reviewed soon');
        
        Log::alert("Transaction has been updated");

        return redirect()->route('transaction');
    }
}
