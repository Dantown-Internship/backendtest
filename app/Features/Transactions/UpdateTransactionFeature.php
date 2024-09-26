<?php

namespace App\Features\Transactions;

use App\Actions\BaseAction;
use App\Actions\TransactionActions;
use App\Services\EventLog;
use Illuminate\Support\Facades\DB;

class UpdateTransactionFeature extends BaseTransactionFeature {

    public function __construct( protected int $transactionId )
    {
        parent::__construct();
    }

    public function handle( BaseAction|TransactionActions $action, array $params = [] )
    {
        DB::transaction( function() use($action, $params){
            
            $transaction = $action->withParams( $params )
                                  ->updateTrasaction( $this->transactionId )
                                  ->getData()['transaction'];

            EventLog::log("Transactions was approved for {$transaction->user->name}. User ID: {$transaction->user->id} || Amount: {$transaction->amount}", $params['user']->id );
        });
      
    }
}