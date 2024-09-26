<?php

namespace App\Providers;

use App\Contracts\ITransactionRepositoryContract;
use App\Contracts\IWalletRepositoryContract;
use App\Repositories\Eloquent\TransactionsRepository;
use App\Repositories\Eloquent\WalletRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
   
    public function boot(): void
    {
        $this->app->bind( ITransactionRepositoryContract::class, TransactionsRepository::class );
        $this->app->bind( IWalletRepositoryContract::class, WalletRepository::class );
    }
}
