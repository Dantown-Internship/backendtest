<?php

namespace App\Features\Transactions;

use App\Actions\BaseAction;
use App\Actions\TransactionActions;
use App\Facades\WalletFacade;
use App\Notifications\TransactionApprovedEmailNotifiaction;
use App\Services\EventLog;
use Illuminate\Support\Facades\DB;

class ApproveTransactionFeature extends BaseTransactionFeature {

    public function __construct( protected int $transactionId )
    {
        parent::__construct();
    }


    public function handle( BaseAction|TransactionActions $action, array $params = [] )
    {
        $transaction = $action->approveTransaction( $this->transactionId )->getData()['transaction'];
        
        if( $transaction ) {

            DB::transaction( function() use($transaction, $params){

                EventLog::log("Transactions was approved for {$transaction->user->name}. User ID: {$transaction->user->id} || Amount: {$transaction->amount}", $params['user']->id );

                WalletFacade::updateBalance( $transaction->user_id );

                EventLog::log("Wallet balance was update for {$transaction->user->name}. User ID: {$transaction->user->id} || Balance: {$transaction->user->walletBalance()}", $params['user']->id );
            });

            $transaction->user->notify( new TransactionApprovedEmailNotifiaction( $transaction ) );
        }    
    }
}