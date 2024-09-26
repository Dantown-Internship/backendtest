<?php

namespace App\Actions;

use App\Contracts\IWalletRepositoryContract;

class WalletActions extends BaseAction { 

    private IWalletRepositoryContract $walletRepository;

    public function __construct()
    {
        $this->walletRepository = app()->get( IWalletRepositoryContract::class );
    }

    public function updateBalance( int $userId ) : static
    {
        $this->walletRepository->updateBalance( $userId );

        return $this;

    }
}