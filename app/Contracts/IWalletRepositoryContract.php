<?php

namespace App\Contracts;

interface IWalletRepositoryContract {

    public function updateBalance( int $userId ) : void;
}