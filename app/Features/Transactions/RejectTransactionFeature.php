<?php

namespace App\Features\Transactions;

use App\Actions\BaseAction;
use App\Actions\TransactionActions;
use App\Facades\WalletFacade;
use App\Notifications\TransactionRejectedEmailNotifiaction;
use App\Services\EventLog;
use Illuminate\Support\Facades\DB;

class RejectTransactionFeature extends BaseTransactionFeature {

    public function __construct( protected int $transactionId )
    {
        parent::__construct();
    }

    public function handle( BaseAction|TransactionActions $action, array $params = [] )
    {
       $transaction = DB::transaction( function() use($action, $params){

            $transaction = $action->rejectTransaction( $this->transactionId )
                                  ->getData()['transaction'];   
        
            EventLog::log("Transactions was rejected for {$transaction->user->name}. User ID: {$transaction->user->id} || Amount: {$transaction->amount}", $params['user']->id );

            WalletFacade::updateBalance( $transaction->user_id );

            EventLog::log("Wallet balance was update for {$transaction->user->name}. User ID: {$transaction->user->id} || Balance: {$transaction->user->walletBalance()}", $params['user']->id );

            return $transaction;

        });

        $transaction->user->notify( new TransactionRejectedEmailNotifiaction( $transaction ) );
       
    }
}