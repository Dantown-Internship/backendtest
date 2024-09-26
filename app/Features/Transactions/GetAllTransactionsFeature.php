<?php

namespace App\Features\Transactions;

use App\Actions\BaseAction;
use App\Actions\TransactionActions;
use App\Models\User;

class GetAllTransactionsFeature extends BaseTransactionFeature {

    public function __construct( protected User $user)
    {
        parent::__construct();
    }

    public function handle( BaseAction|TransactionActions $action, array $params = [] )
    {        
        return $action->getTransactions( $this->user )
                      ->getData()['transactions'];
    }
}