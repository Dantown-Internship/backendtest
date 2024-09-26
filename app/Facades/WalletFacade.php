<?php

namespace App\Facades;

use App\Actions\WalletActions;
use Illuminate\Support\Facades\Facade;

class WalletFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return WalletActions::class;
    }
}