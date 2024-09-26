<?php

namespace App\Http\Controllers\Web\Marker;

use App\Exceptions\TransactionAlreadyApprovedException;
use App\Exceptions\TransactionIsPendingException;
use App\Features\Transactions\CreateTransactionFeature;
use App\Features\Transactions\GetAllTransactionsFeature;
use App\Features\Transactions\UpdateTransactionFeature;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\StoreTransactionRequest;
use App\Http\Requests\Transactions\UpdateTransactionRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index() : View
    {
        $transactions = $this->process( new GetAllTransactionsFeature( auth()->user() ) );

        return view('transactions.index', compact('transactions') );
    }


    public function store( StoreTransactionRequest $request ) : RedirectResponse
    {
        $this->process( new CreateTransactionFeature, $request->validated() + ['user' => auth()->user() ] );

        return redirect()->route('transactions.index')->with('message', 'Transaction successfully created');
    }

    public function update( int $transactionId, UpdateTransactionRequest $request ) : RedirectResponse
    {
        try {
            
            $this->process( new UpdateTransactionFeature( $transactionId ), $request->validated() + ['user' => auth()->user() ]  );

            return redirect()->route('transactions.index')->with('Transaction updated successfully');

        } catch (ModelNotFoundException $th) {
            
            return back()->setStatusCode(403)->with('message', $th->getMessage() );

        }catch( TransactionIsPendingException $th){

            return back()->setStatusCode(403)->with('message', $th->getMessage() );

        }catch( TransactionAlreadyApprovedException $th){

            return back()->setStatusCode(403)->with('message', $th->getMessage() );
        }
       
    }

}
