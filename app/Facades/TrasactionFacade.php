<?php

namespace App\Facades;

use App\Actions\TransactionActions;
use Illuminate\Support\Facades\Facade;

class TransactionFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return TransactionActions::class;
    }
}