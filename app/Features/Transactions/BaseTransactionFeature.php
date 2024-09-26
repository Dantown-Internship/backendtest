<?php

namespace App\Features\Transactions;

use App\Actions\BaseAction;
use App\Actions\TransactionActions;
use App\Contracts\FeaturesContract;

class BaseTransactionFeature extends FeaturesContract {

    public function __construct()
    {
        $this->action = new TransactionActions;
    }

    public function handle( BaseAction $action, array $params = [] ){}
}