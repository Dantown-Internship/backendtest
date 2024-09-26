<?php

namespace App\Features\Transactions;

use App\Actions\BaseAction;
use App\Actions\TransactionActions;
use App\Services\EventLog;
use Illuminate\Support\Facades\DB;

class CreateTransactionFeature extends BaseTransactionFeature {

    public function handle( BaseAction|TransactionActions $action, array $params = [] )
    {
        DB::transaction( function() use($params, $action){

            $transaction = $action->withParams( $params )
                                  ->createTrasaction()
                                  ->getData()['transaction'];

            EventLog::log( $params['user']->name. " created a transaction. || Amount: {$params['amount']} || UserID: {$transaction->user->id}", $params['user']->id );
            
        });
       
    }
}