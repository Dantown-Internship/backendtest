<?php

namespace App\Features\Wallet;

use App\Actions\BaseAction;
use App\Actions\WalletActions;
use App\Contracts\FeatureContract;

class BaseWalletFeature extends FeatureContract {

    public function __construct()
    {
        $this->action = new WalletActions;
    }

    public function handle( BaseAction $action, array $params = [] ){}
}