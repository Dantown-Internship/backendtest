<?php

namespace App\Http\Controllers\Web\Checker;

use App\Exceptions\TransactionAlreadyApprovedException;
use App\Exceptions\TransactionAlreadyRejectedException;
use App\Features\Transactions\ApproveTransactionFeature;
use App\Features\Transactions\RejectTransactionFeature;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\ApproveTransactionRequest;
use App\Http\Requests\Transactions\RejectTransactionRequest;

class TransactionController extends Controller
{
    public function approve( int $transactionId, ApproveTransactionRequest $request )
    {
        
        try {

            $this->process( new ApproveTransactionFeature( $transactionId ), $request->validated() + ['user' => auth()->user() ]  );

            return redirect()->route('transactions.index');

        } catch ( TransactionAlreadyApprovedException $th ) {
            
            return back()->setStatusCode(400)->with('message', $th->getMessage() );
        }
       
    }

    public function reject( int $transactionId, RejectTransactionRequest $request )
    {
        try {
           
            $this->process( new RejectTransactionFeature( $transactionId ), $request->validated() + ['user' => auth()->user() ]  );

            return redirect()->route('transactions.index');

        } catch ( TransactionAlreadyRejectedException $th ) {
           
            return back()->setStatusCode(400)->with('message', $th->getMessage() );
        }
       
    }
}
