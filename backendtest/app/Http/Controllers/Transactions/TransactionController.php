<?php

namespace App\Http\Controllers\Transactions;

use Error;
use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SystemPoolBalance;
use Illuminate\Support\Facades\DB;
use App\Enums\TransactionTypeEnums;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Enums\TransactionStatusEnums;
use Spatie\Activitylog\Models\Activity;
use App\Notifications\TransactionNotification;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TransactionController extends Controller  implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:create transactions', only: ['create','store','update','edit']),
            new Middleware('permission:approve transactions', only: ['approve','reject']),
        ];
    }

    public function get()
    {
        $user = auth()->user();

        if($user->hasRole('SuperAdmin')){
            $query = Transaction::with('user')->orderBy('id','Desc');
        }else if($user->hasRole('Maker')){
            $query = Transaction::with('user')->where('user_id',$user->id)->orderBy('id','Desc');
        }else{
            $query = Transaction::with('user')->where('status','pending')->orWhere('approved_id',$user->id)->orderBy('id','Desc');
        }

        $data = $query->get();
        return $data;
    }
    public function find(string $id)
    {
        $user = auth()->user();

        if($user->hasRole('SuperAdmin')){
            $query = Transaction::with('user')->where('id',$id);
        }else if($user->hasRole('Maker')){
            $query = Transaction::with('user')->where('id',$id)->where('user_id',$user->id);
        }else{
            $query = Transaction::with('user')->where('id',$id)->orWhere('approved_id',$user->id)->where('status','pending');
        }
        $data = $query->first();

        if(!$data){
            return back()->with(['alertError'=>'Not found','errorMessage'=>'Not found']);
/*             successMessage alertSuccess */
          //  throw new Error('Not found');
        }
        return $data;
    }

    public function index()
    {
        $transactions = $this->get();
        $pool_balance = SystemPoolBalance::first()?->pool_balance;
        $activity = Activity::all()->last();
     //   dd($activity->changes);
        return view('users.dashboard.dashboard', compact('transactions','pool_balance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('users.transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required',Rule::in(TransactionTypeEnums::values())],
            'amount' => 'required|numeric|min:0.1',
            'description' => 'nullable|string',
        ]);

        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'type' => $request->input('type'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'status' => TransactionStatusEnums::PENDING->value,
        ]);

        return redirect()->route('transaction.index')->with(['successMessage'=>'Transaction successfully created! Waiting Approval ','alertSuccess'=>'Transaction successfully created! Waiting Approval']);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = $this->find(id:$id);

        return view('users.transaction.edit',compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type' => ['required',Rule::in(TransactionTypeEnums::values())],
            'amount' => 'required|numeric|min:0.1',
            'description' => 'nullable|string',
        ]);
        $transaction = $this->find(id:$id);

        $transaction->update([
            'type' => $request->input('type'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'status' => TransactionStatusEnums::PENDING->value,
        ]);

        return redirect()->route('transaction.index')->with(['successMessage'=>'Transaction successfully Updated! Waiting Approval ','alertSuccess'=>'Transaction successfully Updated! Waiting Approval']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function approve(string $id)
    {
        $transaction = $this->find($id);
       try{
        $wallet = $transaction->user->wallet;
            $systemPoolBalance = SystemPoolBalance::first();

            if ($transaction->type == 'credit') {
                if($systemPoolBalance->pool_balance < $transaction->amount){
                    return back()->with(['alertError' => 'Transaction failed', 'errorMessage' => 'Insuffient Balance']);
                }
                $wallet->increment('balance', $transaction->amount);
                $systemPoolBalance->decrement('pool_balance', $transaction->amount);
            } elseif ($transaction->type == 'debit') {
                if($wallet->balance<$transaction->amount){
                    return back()->with(['alertError' => 'Transaction failed', 'errorMessage' => 'Insuffient Balance']);
                }
                $wallet->decrement('balance', $transaction->amount);
                $systemPoolBalance->increment('pool_balance', $transaction->amount);
            }

            $transaction->update([
                    'status' => 'approved',
                    'approved_id' => auth()->user()->id
            ]);
            $transaction->user->notify(new TransactionNotification($transaction, 'approved'));

        return redirect()->route('transaction.index')->with(['successMessage'=>'Transaction approved successfully.','alertSuccess'=>'Transaction approved successfully.']);
       }catch(Exception  $e){
            return back()->with(['alertError' => 'Transaction failed', 'errorMessage' => $e->getMessage()]);
       }
    }

    public function reject(string $id)
    {
        $transaction = $this->find($id);

        $transaction->update([
            'status' => 'rejected',
            'approved_id' => auth()->user()->id
        ]);

        $transaction->user->notify(new TransactionNotification($transaction, 'rejected'));

        return redirect()->route('transaction.index')->with(['successMessage'=>'Transaction rejected successfully.','alertSuccess'=>'Transaction rejected successfully.']);
    }
}
