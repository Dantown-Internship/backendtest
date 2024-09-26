<?php

namespace App\Features\Transactions;

use App\Actions\BaseAction;
use App\Actions\TransactionActions;

class GetTransactionFeature extends BaseTransactionFeature {

    public function __construct( protected int $transactionId )
    {
        parent::__construct();
    }

    public function handle( BaseAction|TransactionActions $action, array $params = [] )
    {
        return $action->getTransaction( $this->transactionId )
                      ->getData()['transaction'];
    }
}