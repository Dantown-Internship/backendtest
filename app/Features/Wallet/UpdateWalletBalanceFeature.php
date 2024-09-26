<?php

namespace App\Features\Wallet;

use App\Actions\BaseAction;
use App\Actions\WalletActions;

class UpdateWalletBalanceFeature extends BaseWalletFeature {

    public function handle( BaseAction|WalletActions $action, array $params = [] ){}
}