<?php

namespace App\Repositories\Eloquent;

use App\Contracts\IWalletRepositoryContract;
use App\Models\User;

class WalletRepository implements IWalletRepositoryContract {

    public function updateBalance( int $userId ) : void
    {
        User::find( $userId )->updateWalletBalance();
    }
}